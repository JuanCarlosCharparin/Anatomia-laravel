<?php

namespace App\Http\Controllers;
use App\Models\Estudio;
use App\Models\DetallePap;
use App\Models\DetalleEstudio;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class EstudioController extends Controller
{
    public function edit($nro_servicio)
    {
        // Ejecutar la consulta
        $estudio = DB::connection('mysql')->table('estudio as e')
            ->select(
                'e.nro_servicio as nro_servicio',
                's.nombre_servicio as servicio',
                'tde.nombre as tipo_estudio',
                'e.estado_estudio as estado',
                DB::raw("CONCAT(p.nombres, ' ', p.apellidos) as paciente"),
                'p.obra_social as obra_social',
                'e.diagnostico_presuntivo as diagnostico',
                'e.fecha_carga as fecha_carga',
                DB::raw("CONCAT(prof.nombres, ' ', prof.apellidos) as profesional"),
                'de.macro',
                'de.fecha_macro',
                'de.micro',
                'de.fecha_inclusion',
                'de.fecha_corte',
                'de.fecha_entrega',
                'de.conclusion',
                'de.observacion',
                'de.maligno',
                'de.observacion_interna',
                'de.recibe',
                'de.tacos',
                'de.diagnostico_presuntivo',
                'de.tecnicas',
                'dp.estado_especimen',
                'dp.celulas_pavimentosas',
                'dp.celulas_cilindricas',
                'dp.valor_hormonal',
                'dp.fecha_lectura',
                'dp.valor_hormonal_HC',
                'dp.cambios_reactivos',
                'dp.cambios_asoc_celula_pavimentosa',
                'dp.cambios_celula_glandulares',
                'dp.celula_metaplastica',
                'dp.otras_neo_malignas',
                'dp.toma',
                'dp.recomendaciones',
                'dp.microorganismos',
                'dp.resultado'
            )
            ->leftJoin('tipo_de_estudio as tde', 'e.tipo_estudio_id', '=', 'tde.id')
            ->leftJoin('servicio as s', 'e.servicio_id', '=', 's.id')
            ->leftJoin('personal as p', 'e.personal_id', '=', 'p.id')
            ->leftJoin('profesional as prof', 'e.profesional_id', '=', 'prof.id')
            ->leftJoin('detalle_estudio as de', 'e.detalle_estudio_id', '=', 'de.id')
            ->leftJoin('detalle_pap as dp', 'e.detalle_pap_id', '=', 'dp.id')
            ->where('e.nro_servicio', $nro_servicio)
            ->first();

        // Decodificar el JSON de estado_especimen
        $estudio->estado_especimen = json_decode($estudio->estado_especimen, true);
        $estudio->celulas_pavimentosas = json_decode($estudio->celulas_pavimentosas, true);
        $estudio->celulas_cilindricas = json_decode($estudio->celulas_cilindricas, true);
        $estudio->cambios_reactivos = json_decode($estudio->cambios_reactivos, true);
        $estudio->cambios_asoc_celula_pavimentosa = json_decode($estudio->cambios_asoc_celula_pavimentosa, true);
        $estudio->celula_metaplastica = json_decode($estudio->celula_metaplastica, true);
        $estudio->toma = json_decode($estudio->toma, true);
        $estudio->recomendaciones = json_decode($estudio->recomendaciones, true);
        $estudio->microorganismos = json_decode($estudio->microorganismos, true);
        $estudio->resultado = json_decode($estudio->resultado, true);


        // Obtener los materiales asociados al nro_servicio
        $materiales = DB::connection('mysql')->table('material')
        ->select('material')
        ->where('nro_servicio', $nro_servicio)
        ->get();

        // Determinar la vista del formulario según el tipo de estudio
        $view = $estudio->tipo_estudio === 'Pap' ? 'estudios.edit_pap' : 'estudios.edit_detalle';

        // Pasar el estudio a la vista de edición
        return view($view, compact('estudio', 'materiales'));
    }

    public function update(Request $request, $nro_servicio)
    {
        $estudio = Estudio::where('nro_servicio', $nro_servicio)->firstOrFail();

        if ($estudio->tipo_estudio_id === 3) {

            // Validar la entrada del usuario en caso pap
            $validatedData = $request->validate([
                'estado_especimen' => 'nullable|array',
                'estado_especimen.*' => 'string|distinct|max:255',

                'celulas_pavimentosas' => 'nullable|array',
                'celulas_pavimentosas.*' => 'string|distinct|max:255',

                'celulas_cilindricas' => 'nullable|array',
                'celulas_cilindricas.*' => 'string|distinct|max:255',

                'valor_hormonal' => 'nullable|string|max:255',
                'fecha_lectura' => 'nullable|date',
                'valor_hormonal_HC' => 'nullable|string|max:255',

                'cambios_reactivos' => 'nullable|array',
                'cambios_reactivos.*' => 'string|distinct|max:255',

                'cambios_asoc_celula_pavimentosa' => 'nullable|array',
                'cambios_asoc_celula_pavimentosa.*' => 'string|distinct|max:255',

                'cambios_celula_glandulares' => 'nullable|string|max:255',

                'celula_metaplastica' => 'nullable|array',
                'celula_metaplastica.*' => 'string|distinct|max:255',

                'otras_neo_malignas' => 'nullable|string|max:255',

                'toma' => 'nullable|array',
                'toma.*' => 'string|distinct|max:255',

                'recomendaciones' => 'nullable|array',
                'recomendaciones.*' => 'string|distinct|max:255',

                'microorganismos' => 'nullable|array',
                'microorganismos.*' => 'string|distinct|max:255',

                'resultado' => 'nullable|array',
                'resultado.*' => 'string|distinct|max:255',
            ]);

            // Convertir a JSON
            $validatedData['estado_especimen'] = isset($validatedData['estado_especimen']) ? json_encode($validatedData['estado_especimen']) : null;
            $validatedData['celulas_pavimentosas'] = isset($validatedData['celulas_pavimentosas']) ? json_encode($validatedData['celulas_pavimentosas']) : null;
            $validatedData['celulas_cilindricas'] = isset($validatedData['celulas_cilindricas']) ? json_encode($validatedData['celulas_cilindricas']) : null;
            $validatedData['valor_hormonal'] = $validatedData['valor_hormonal'] ?? null;
            $validatedData['fecha_lectura'] = $validatedData['fecha_lectura'] ?? null;
            $validatedData['valor_hormonal_HC'] = $validatedData['valor_hormonal_HC'] ?? null;
            $validatedData['cambios_reactivos'] = isset($validatedData['cambios_reactivos']) ? json_encode($validatedData['cambios_reactivos']) : null;
            $validatedData['cambios_asoc_celula_pavimentosa'] = isset($validatedData['cambios_asoc_celula_pavimentosa']) ? json_encode($validatedData['cambios_asoc_celula_pavimentosa']) : null;
            $validatedData['cambios_celula_glandulares'] = $validatedData['cambios_celula_glandulares'] ?? null;
            $validatedData['celula_metaplastica'] = isset($validatedData['celula_metaplastica']) ? json_encode($validatedData['celula_metaplastica']) : null;
            $validatedData['otras_neo_malignas'] = $validatedData['otras_neo_malignas'] ?? null;
            $validatedData['toma'] = isset($validatedData['toma']) ? json_encode($validatedData['toma']) : null;
            $validatedData['recomendaciones'] = isset($validatedData['recomendaciones']) ? json_encode($validatedData['recomendaciones']) : null;
            $validatedData['microorganismos'] = isset($validatedData['microorganismos']) ? json_encode($validatedData['microorganismos']) : null;
            $validatedData['resultado'] = isset($validatedData['resultado']) ? json_encode($validatedData['resultado']) : null;

            // Verificar si el detalle_pap ya existe
            $detallePapId = $estudio->detalle_pap_id;
            if ($detallePapId) {
                $detallePap = DetallePap::find($detallePapId);
            } else {
                $detallePap = new DetallePap();
            }

            // Actualizar el detalle_pap solo con los campos que tienen valores nuevos
            $detallePap->fill(array_filter($validatedData, function($value) {
                return !is_null($value) && $value !== '';
            }));

            // Guardar el detalle_pap
            $detallePap->save();
            
            // Obtener el ID del detalle_pap
            $detallePapId = $detallePap->id;

            // Actualizar el registro de Estudio con el id del DetallePap
            $estudio->update([
                'detalle_pap_id' => $detallePapId,
                'estado_estudio' => 'informando',
            ]);

        } else {
            // Validar la entrada del usuario en caso detalle_estudio
            $validatedData = $request->validate([
                'macro' => 'nullable|string|max:255',
                'fecha_macro' => 'nullable|date',
                'micro' => 'nullable|string|max:255',
                'fecha_inclusion' => 'nullable|date',
                'fecha_corte' => 'nullable|date',
                'fecha_entrega' => 'nullable|date',
                'conclusion' => 'nullable|string|max:255',
                'observacion' => 'nullable|string|max:255',
                'maligno' => 'nullable|string|max:255',
                'guardado' => 'nullable|string|max:255',
                'observacion_interna' => 'nullable|string|max:255',
                'recibe' => 'nullable|string|max:255',
                'tacos' => 'nullable|string|max:255',
                'diagnostico_presuntivo' => 'nullable|string|max:255',
                'tecnicas' => 'nullable|string|max:255',
            ]);
        
            // Verificar si el detalle_estudio ya existe
            $detalleEstudioId = $estudio->detalle_estudio_id;
            if ($detalleEstudioId) {
                $detalleEstudio = DetalleEstudio::find($detalleEstudioId);
            } else {
                $detalleEstudio = new DetalleEstudio();
            }
        
            // Actualizar el detalle_estudio solo con los campos que tienen valores nuevos
            $detalleEstudio->fill(array_filter($validatedData, function($value) {
                return !is_null($value) && $value !== '';
            }));
        
            // Guardar el detalle_estudio
            $detalleEstudio->save();
            
            // Obtener el ID del detalle_estudio
            $detalleEstudioId = $detalleEstudio->id;
        
            // Actualizar el registro de Estudio con el id del DetalleEstudio
            $estudio->update([
                'detalle_estudio_id' => $detalleEstudioId,
                'estado_estudio' => 'informando',
            ]);
    
        }    
        return redirect()->route('estudios.edit', ['nro_servicio' => $nro_servicio])->with('success', 'Estudio actualizado con éxito');
    }
}
