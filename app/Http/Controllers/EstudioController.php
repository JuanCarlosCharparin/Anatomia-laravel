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
                'de.fecha_micro',
                'de.conclusion',
                'de.observacion',
                'de.maligno',
                'de.guardado',
                'de.observacion_interna',
                'de.recibe',
                'de.tacos',
                'de.diagnostico_presuntivo',
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
                'estado_especimen' => 'required|array',
                'estado_especimen.*' => 'string|distinct|max:255',

                'celulas_pavimentosas' => 'required|array',
                'celulas_pavimentosas.*' => 'string|distinct|max:255',

                'celulas_cilindricas' => 'required|array',
                'celulas_cilindricas.*' => 'string|distinct|max:255',

                'valor_hormonal' => 'nullable|string|max:255',
                'fecha_lectura' => 'nullable|date',
                'valor_hormonal_HC' => 'required|string|max:255',

                'cambios_reactivos' => 'required|array',
                'cambios_reactivos.*' => 'string|distinct|max:255',

                'cambios_asoc_celula_pavimentosa' => 'required|array',
                'cambios_asoc_celula_pavimentosa.*' => 'string|distinct|max:255',

                'cambios_celula_glandulares' => 'nullable|string|max:255',

                'celula_metaplastica' => 'required|array',
                'celula_metaplastica.*' => 'string|distinct|max:255',

                'otras_neo_malignas' => 'nullable|string|max:255',

                'toma' => 'required|array',
                'toma.*' => 'string|distinct|max:255',
                'recomendaciones' => 'required|array',
                'recomendaciones.*' => 'string|distinct|max:255',
                'microorganismos' => 'required|array',
                'microorganismos.*' => 'string|distinct|max:255',
                'resultado' => 'required|array',
                'resultado.*' => 'string|distinct|max:255',
            ]);

            // Convertir a JSON
            $validatedData['estado_especimen'] = json_encode($validatedData['estado_especimen']);
            $validatedData['celulas_pavimentosas'] = json_encode($validatedData['celulas_pavimentosas']);
            $validatedData['celulas_cilindricas'] = json_encode($validatedData['celulas_cilindricas']);
            $validatedData['cambios_reactivos'] = json_encode($validatedData['cambios_reactivos']);
            $validatedData['cambios_asoc_celula_pavimentosa'] = json_encode($validatedData['cambios_asoc_celula_pavimentosa']);
            $validatedData['cambios_celula_glandulares'] = $validatedData['cambios_celula_glandulares'] ?? null; // Handle null value
            $validatedData['celula_metaplastica'] = json_encode($validatedData['celula_metaplastica']);
            $validatedData['otras_neo_malignas'] = $validatedData['otras_neo_malignas'] ?? null; // Handle null value
            $validatedData['toma'] = json_encode($validatedData['toma']);
            $validatedData['recomendaciones'] = json_encode($validatedData['recomendaciones']);
            $validatedData['microorganismos'] = json_encode($validatedData['microorganismos']);
            $validatedData['resultado'] = json_encode($validatedData['resultado']);

            // Verificar si el detalle_pap ya existe
            $detallePap = DetallePap::where([
                'estado_especimen' => $validatedData['estado_especimen'],
                'celulas_pavimentosas' => $validatedData['celulas_pavimentosas'],
                'celulas_cilindricas' => $validatedData['celulas_cilindricas'],
                'valor_hormonal' => $validatedData['valor_hormonal'],
                'fecha_lectura' => $validatedData['fecha_lectura'],
                'valor_hormonal_HC' => $validatedData['valor_hormonal_HC'],
                'cambios_reactivos' => $validatedData['cambios_reactivos'],
                'cambios_asoc_celula_pavimentosa' => $validatedData['cambios_asoc_celula_pavimentosa'],
                'cambios_celula_glandulares' => $validatedData['cambios_celula_glandulares'],
                'celula_metaplastica' => $validatedData['celula_metaplastica'],
                'otras_neo_malignas' => $validatedData['otras_neo_malignas'],
                'toma' => $validatedData['toma'],
                'recomendaciones' => $validatedData['recomendaciones'],
                'microorganismos' => $validatedData['microorganismos'],
                'resultado' => $validatedData['resultado'],
            ])->first();

            // Crear o actualizar el detalle_pap
            if ($detallePap) {
                $detallePap->update($validatedData);
            } else {
                $detallePap = DetallePap::create($validatedData);
            }
            $detallePapId = $detallePap->id;

            // Actualizar el registro de Estudio con el id del DetallePap
            $estudio->update([
                'detalle_pap_id' => $detallePapId,
                'estado_estudio' => 'informando',
            ]);

        } else {
            // Validar la entrada del usuario en caso detalle_estudio
            $validatedData = $request->validate([
                'macro' => 'required|string|max:255',
                'fecha_macro' => 'nullable|date',
                'micro' => 'required|string|max:255',
                /*'fecha_micro' => 'nullable|date',*/
                'conclusion' => 'required|string|max:255',
                'observacion' => 'required|string|max:255',
                'maligno' => 'required|string|max:255',
                'guardado' => 'nullable|string|max:255',
                'observacion_interna' => 'required|string|max:255',
                'recibe' => 'nullable|string|max:255',
                'tacos' => 'nullable|string|max:255',
                'diagnostico_presuntivo' => 'required|string|max:255',
                'tecnicas' => 'required|string|max:255',
            ]);
           

            // Verificar si el detalle_estudio ya existe
            $detalleEstudio = DetalleEstudio::where([
                'macro' => $validatedData['macro'],
                'fecha_macro' => $validatedData['fecha_macro'],
                'micro' => $validatedData['micro'],
                /*'fecha_micro' => $validatedData['fecha_micro'],*/
                'conclusion' => $validatedData['conclusion'],
                'observacion' => $validatedData['observacion'],
                'maligno' => $validatedData['maligno'],
                'guardado' => $validatedData['guardado'],
                'observacion_interna' => $validatedData['observacion_interna'],
                'recibe' => $validatedData['recibe'],
                'tacos' => $validatedData['tacos'],
                'diagnostico_presuntivo' => $validatedData['diagnostico_presuntivo'],
                'tecnicas' => $validatedData['tecnicas'],
            ])->first();

            // Crear o actualizar el detalle_estudio
            if ($detalleEstudio) {
                $detalleEstudio->update($validatedData);
            } else {
                $detalleEstudio = DetalleEstudio::create($validatedData);
            }
            $detalleEstudioId = $detalleEstudio->id;

            // Actualizar el registro de Estudio con el id del DetalleEstudio
            $estudio->update([
                'detalle_estudio_id' => $detalleEstudioId,
                'estado_estudio' => 'informando',
            ]);
        }

        // Redirigir al índice con un mensaje de éxito
        return redirect()->route('estudios.index')->with('success', 'Estudio actualizado con éxito');
    }
}
