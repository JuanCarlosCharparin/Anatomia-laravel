<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Estudio;
use App\Models\Paciente;
use App\Models\Personal; 
use App\Models\Profesional;
use App\Models\Especialidad;
use App\Models\Servicio;
use App\Models\CodigoNomencladorAP;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;

class ModificarEstudioController extends Controller
{
    public function modify($nro_servicio)
    {
        // Obtén el estudio y sus detalles
        $estudio = DB::connection('mysql')->table('estudio as e')
            ->select(
                'e.nro_servicio as nro_servicio',
                's.nombre_servicio as servicio',
                's.servicio_salutte_id as servicio_salutte_id',
                'tde.nombre as tipo_estudio',
                'tde.id as tipo_estudio_id',
                'e.estado_estudio as estado',
                DB::raw("CONCAT(p.nombres, ' ', p.apellidos) as paciente"),
                'p.obra_social as obra_social',
                'p.documento as documento',
                'e.diagnostico_presuntivo as diagnostico',
                'e.medico_solicitante as medico',
                'e.fecha_carga as fecha_carga',
                DB::raw("CONCAT(prof.nombres, ' ', prof.apellidos) as profesional"),
                'prof.profesional_salutte_id AS profesional_salutte_id'
            )
            ->leftJoin('tipo_de_estudio as tde', 'e.tipo_estudio_id', '=', 'tde.id')
            ->leftJoin('servicio as s', 'e.servicio_id', '=', 's.id')
            ->leftJoin('personal as p', 'e.personal_id', '=', 'p.id')
            ->leftJoin('profesional as prof', 'e.profesional_id', '=', 'prof.id')
            ->where('e.nro_servicio', $nro_servicio)
            ->first();
        

        // Obtener la lista de materiales asociados con el nro_servicio
        $materiales = DB::table('material')
        ->where('nro_servicio', $nro_servicio)
        ->pluck('material')
        ->toArray();

        $codigos = DB::table('codigo_nomenclador_ap')
        ->where('nro_servicio', $nro_servicio)
        ->pluck('codigo')
        ->toArray();

        $professionals = Paciente::getProfessionals();
        $servicios = Especialidad::getServicio();
        
        // Devuelve la vista con los datos necesarios
        return view('estudios.modify', compact('estudio', 'materiales', 'servicios', 'codigos'));
    }

    public function updateEstudio(Request $request, $nro_servicio)
    {  
        $validatedData = $request->validate([ 
            'documento' => [function ($attribute, $value, $fail) {
                // Verificar existencia en db2
                $paciente = Paciente::findByDni($value);
                if (!$paciente) {
                    $fail('El paciente no existe en la base de datos.');
                }
            }],
            'tipo_estudio' => 'nullable|integer|exists:tipo_de_estudio,id',
            'profesional_salutte_id' => 'nullable|exists:profesional,profesional_salutte_id', // Cambiado a nullable
            'servicio_salutte_id' => ['nullable', function ($attribute, $value, $fail) {
                // Verificar existencia en db2
                $servicio = Especialidad::where('id', $value)->first();
                if (!$servicio) {
                    $fail('El servicio no existe en la base de datos.');
                }
            }],
            'diagnostico' => 'nullable|string|max:500',
            'medico_solicitante' => 'nullable|string|max:255',
            'materiales' => 'nullable|array',
            'materiales.*' => 'nullable|string|max:255',
            'codigos' => 'nullable|array',
            'codigos.*' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            // Obtener el paciente de la base de datos secundaria
            $documento = $validatedData['documento'];
            $paciente = Paciente::findByDni($documento);

            // Actualizar el paciente en la base de datos principal
            $pacienteDb = Personal::updateOrCreate(
                ['documento' => $paciente->documento],
                [
                    'persona_salutte_id' => $paciente->id,
                    'nombres' => $paciente->nombres,
                    'apellidos' => $paciente->apellidos,
                    'obra_social' => $paciente->obra_social,
                    'fecha_nacimiento' => $paciente->fecha_nacimiento,
                    'genero' => $paciente->genero,
                ]
            );

            // Obtener el profesional de la base de datos secundaria
            $profesionalSalutteId = $validatedData['profesional_salutte_id'];
            if ($profesionalSalutteId) {
                $profesional = Profesional::where('profesional_salutte_id', $profesionalSalutteId)->first();
                if (!$profesional) {
                    return redirect()->back()->with('error', 'El profesional seleccionado no existe en la base de datos.');
                }
            }

            // Obtener el servicio desde la base de datos secundaria
            $servicioSalutteId = $validatedData['servicio_salutte_id'];
            $servicio = Especialidad::findServicioById($servicioSalutteId);

            if (!$servicio) {
                return redirect()->back()->with('error', 'Servicio no encontrado.');
            }

            // Crear o actualizar el servicio en la base de datos principal
            $servicioDb = Servicio::updateOrCreate(
                ['servicio_salutte_id' => $servicioSalutteId],
                ['nombre_servicio' => $servicio->nombre_servicio]
            );

            // Obtener el ID del servicio en la base de datos principal
            $servicioId = $servicioDb->id;

            // Actualizar el estudio
            $estudio = Estudio::where('nro_servicio', $nro_servicio)->firstOrFail();

            $estudio->update([
                'personal_id' => $pacienteDb->id,
                'tipo_estudio_id' => $validatedData['tipo_estudio'],
                'profesional_id' => $profesional ? $profesional->id : $estudio->profesional_id, // Usar el ID del profesional si existe
                'servicio_id' => $servicioId,
                'diagnostico_presuntivo' => $validatedData['diagnostico'],
                'medico_solicitante' => $validatedData['medico_solicitante'],
                'updatedBy' => Auth::id(),
            ]);


            // Obtener todos los materiales actuales para el estudio
            $existingMaterials = DB::table('material')
            ->where('nro_servicio', $nro_servicio)
            ->pluck('material')
            ->toArray();

            // Materiales enviados en el formulario
            $materiales = $validatedData['materiales'] ?? [];

            // Materiales a mantener en la base de datos
            $materialesToKeep = array_filter($materiales, fn($material) => !empty($material));

            // Materiales a eliminar (los que estaban en la base de datos pero no están en el formulario)
            $materialsToDelete = array_diff($existingMaterials, $materialesToKeep);

            if (!empty($materialsToDelete)) {
                DB::table('material')
                    ->where('nro_servicio', $nro_servicio)
                    ->whereIn('material', $materialsToDelete)
                    ->delete();
            }

            // Materiales a agregar (los que están en el formulario pero no están en la base de datos)
            $materialsToInsert = array_diff($materialesToKeep, $existingMaterials);

            if (!empty($materialsToInsert)) {
                DB::table('material')->insert(
                    array_map(fn($material) => ['nro_servicio' => $nro_servicio, 'material' => $material], $materialsToInsert)
                );
            }

            // Obtener todos los códigos actuales para el estudio
            $existingCodigos = DB::table('codigo_nomenclador_ap')
            ->where('nro_servicio', $nro_servicio)
            ->pluck('codigo')
            ->toArray();

            // Códigos enviados en el formulario
            $codigos = $validatedData['codigos'] ?? [];

            // Códigos a mantener en la base de datos
            $codigosToKeep = array_filter($codigos, fn($codigo) => !empty($codigo));

            // Códigos a eliminar (los que estaban en la base de datos pero no están en el formulario)
            $codigosToDelete = array_diff($existingCodigos, $codigosToKeep);

            if (!empty($codigosToDelete)) {
                DB::table('codigo_nomenclador_ap')
                    ->where('nro_servicio', $nro_servicio)
                    ->whereIn('codigo', $codigosToDelete)
                    ->delete();
            }

            // Códigos a agregar (los que están en el formulario pero no están en la base de datos)
            $codigosToInsert = array_diff($codigosToKeep, $existingCodigos);

            if (!empty($codigosToInsert)) {
                DB::table('codigo_nomenclador_ap')->insert(
                    array_map(fn($codigo) => ['nro_servicio' => $nro_servicio, 'codigo' => $codigo], $codigosToInsert)
                );
            }

            DB::commit();
            
            //Obtener la posicion mediante una consulta para redireccionar
            $posicion = Estudio::getPosition($nro_servicio);
            $estudios_por_pagina = 20;
            $pagina = ceil($posicion / $estudios_por_pagina);

            
            return redirect()->route('estudios.index', [
                'page' => $pagina,
                'finalizado' => $nro_servicio
            ])->with('success', 'Estudio modificado con éxito');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('estudios.index')->with('error', 'Ocurrió un error al actualizar el estudio: ' . $e->getMessage());
        }
    }
}

