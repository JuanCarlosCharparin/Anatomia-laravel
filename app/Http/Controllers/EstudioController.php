<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Estudio;
use App\Models\DetallePap;
use App\Models\DetalleEstudio;
use App\Models\DetalleEstudioFinalizado;
use App\Models\DetallePapFinalizado;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

use Illuminate\Http\Request;

class EstudioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit($nro_servicio)
    {

        $user = Auth::user();

        if (auth()->check()) {
            $roles = $user->getRoleNames()->toArray();
            // Continúa con la lógica adicional
        } else {
            return redirect()->route('login');
        }

        $page = request('page');
        // Ejecutar la consulta
        $estudio = DB::connection('mysql')->table('estudio as e')
            ->select(
                'e.nro_servicio as nro_servicio',
                's.nombre_servicio as servicio',
                'tde.nombre as tipo_estudio',
                'e.estado_estudio as estado',
                'e.enviado as enviado',
                DB::raw("CONCAT(p.nombres, ' ', p.apellidos) as paciente"),
                DB::raw("
                    CASE 
                        WHEN p.genero = 'm' THEN 'Masculino'
                        WHEN p.genero = 'f' THEN 'Femenino'
                        ELSE 'Desconocido'
                    END AS genero
                "),
                'p.fecha_nacimiento as fecha_nacimiento',
                DB::raw('TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) AS edad'),
                'p.obra_social as obra_social',
                'e.diagnostico_presuntivo as diagnostico',
                'e.medico_solicitante as medico',
                'e.fecha_carga as fecha_carga',
                DB::raw("CONCAT(prof.nombres, ' ', prof.apellidos) as profesional"),
                'de.macro',
                'de.fecha_macro',
                'de.micro',
                'de.fecha_inclusion',
                'de.fecha_corte',
                'de.fecha_entrega',
                'de.observacion',
                'de.maligno',
                'de.observacion_interna',
                'def.recibe',
                'def.tacos',
                'def.ampliar_informe',
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
            ->leftJoin('detalle_estudio_finalizado as def', 'e.detalle_estudio_finalizado_id', '=', 'def.id')
            ->leftJoin('detalle_pap as dp', 'e.detalle_pap_id', '=', 'dp.id')
            ->where('e.nro_servicio', $nro_servicio)
            ->first();

        // Verifica si el estudio fue encontrado
        if (!$estudio) {
            return redirect()->route('error.notfound'); // O una ruta de error adecuada
        }

        // Decodificar el JSON de estado_especimen y otros campos
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

        $estudio->tecnicas = json_decode($estudio->tecnicas, true);

        // Obtener los materiales asociados al nro_servicio
        $materiales = DB::connection('mysql')->table('material')
            ->select('material')
            ->where('nro_servicio', $nro_servicio)
            ->get();

        // Obtener los materiales asociados al nro_servicio
        $codigos = DB::connection('mysql')->table('codigo_nomenclador_ap')
            ->select('codigo')
            ->where('nro_servicio', $nro_servicio)
            ->get();


        // Determinar la vista del formulario según el tipo de estudio
        $view = $estudio->tipo_estudio === 'Pap' ? 'estudios.edit_pap' : 'estudios.edit_detalle';

        // Obtén el usuario autenticado
        $user = Auth::user();
        $roles = $user->getRoleNames(); // Obtiene todos los roles del usuario

        // Pasar el estudio y roles a la vista de edición
        return view($view, compact('estudio', 'materiales', 'roles', 'codigos', 'page'));
    }

    public function update(Request $request, $nro_servicio)
    {

        // Verifica si el usuario tiene permiso para actualizar estudios
        if (!auth()->user()->can('estudios.update')) {
            return redirect()->route('estudios.edit', ['nro_servicio' => $nro_servicio])
                ->with('error', 'No tienes permiso para editar estudios.');
        }

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
                'cambios_celula_glandulares' => 'nullable|string|max:500',
                'celula_metaplastica' => 'nullable|array',
                'celula_metaplastica.*' => 'string|distinct|max:255',
                'otras_neo_malignas' => 'nullable|string|max:500',
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
            $detallePap->fill(array_filter($validatedData, function ($value) {
                return !is_null($value) && $value !== '';
            }));

            $detallePap->updatedBy = Auth::id(); // Asegúrate de que el usuario esté autenticado

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
                'macro' => 'nullable|string|max:500',
                'fecha_macro' => 'nullable|date',
                'micro' => 'nullable|string|max:500',
                'fecha_inclusion' => 'nullable|date',
                'fecha_corte' => 'nullable|date',
                'fecha_entrega' => 'nullable|date',
                'observacion' => 'nullable|string|max:500',
                'maligno' => 'nullable|string|max:255',
                'observacion_interna' => 'nullable|string|max:500',
                /*'recibe' => 'nullable|string|max:255',
                'tacos' => 'nullable|string|max:255',*/
                'diagnostico_presuntivo' => 'nullable|string|max:500',
                'tecnicas' => 'nullable|array',
                'tecnicas.*' => 'string|distinct|max:500',
            ]);

            $validatedData['tecnicas'] = isset($validatedData['tecnicas']) ? json_encode($validatedData['tecnicas']) : null;

            // Verificar si el detalle_estudio ya existe
            $detalleEstudioId = $estudio->detalle_estudio_id;
            if ($detalleEstudioId) {
                $detalleEstudio = DetalleEstudio::find($detalleEstudioId);
            } else {
                $detalleEstudio = new DetalleEstudio();
            }

            // Actualizar el detalle_estudio solo con los campos que tienen valores nuevos
            $detalleEstudio->fill(array_filter($validatedData, function ($value) {
                return !is_null($value) && $value !== '';
            }));

            // Establecer el ID del usuario que está haciendo la actualización
            $detalleEstudio->updatedBy = Auth::id(); // Asegúrate de que el usuario esté autenticado

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

    //Metodo para finalizar

    public function finally(Request $request, $nro_servicio)
    {
        // Verifica si el usuario tiene permiso para finalizar estudios
        if (!auth()->user()->can('estudios.finally')) {
            return redirect()->route('estudios.edit', ['nro_servicio' => $nro_servicio])
                ->with('error', 'No tienes permiso para finalizar estudios.');
        }

        $estudio = Estudio::where('nro_servicio', $nro_servicio)->firstOrFail();

        if ($estudio->tipo_estudio_id === 3) {

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
                'otras_neo_malignas' => 'nullable|string|max:500',
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

            $detallePapFinalizadoId = $estudio->detalle_pap_finalizado_id;
            if ($detallePapFinalizadoId) {
                $detallePapFinalizado = DetallePapFinalizado::find($detallePapFinalizadoId);
            } else {
                $detallePapFinalizado = new DetallePapFinalizado();
            }

            $detallePapFinalizado->fill($validatedData);

            $detallePapFinalizado->createdBy = Auth::id();
            $detallePapFinalizado->createdAt = now();

            // Guardar el detalle_estudio_finalizado
            $detallePapFinalizado->save();

            // Obtener el ID del detalle_estudio_finalizado
            $detallePapFinalizadoId = $detallePapFinalizado->id;

            // Actualizar el registro de Estudio con el id del DetalleEstudioFinalizado
            $updated = $estudio->update([
                'detalle_pap_finalizado_id' => $detallePapFinalizadoId,
                'estado_estudio' => 'finalizado',
            ]);



        } else {
            // Validar la entrada del usuario en caso detalle_estudio_finalizado
            $validatedData = $request->validate([
                'macro' => 'nullable|string|max:500',
                'fecha_macro' => 'nullable|date',
                'micro' => 'nullable|string|max:500',
                'fecha_inclusion' => 'nullable|date',
                'fecha_corte' => 'nullable|date',
                'fecha_entrega' => 'nullable|date',
                'observacion' => 'nullable|string|max:500',
                'maligno' => 'nullable|string|max:500',
                'observacion_interna' => 'nullable|string',
                // No incluir 'recibe' y 'tacos' en la validación aquí
                'diagnostico_presuntivo' => 'nullable|string|max:500',
                'tecnicas' => 'nullable|array',
                'tecnicas.*' => 'string|distinct|max:500',
            ]);

            $validatedData['tecnicas'] = isset($validatedData['tecnicas']) ? json_encode($validatedData['tecnicas']) : null;

            // Verificar si el detalle_estudio_finalizado ya existe
            $detalleEstudioFinalizadoId = $estudio->detalle_estudio_finalizado_id;

            if ($detalleEstudioFinalizadoId) {
                $detalleEstudioFinalizado = DetalleEstudioFinalizado::find($detalleEstudioFinalizadoId);
            } else {
                // Crear un nuevo detalle_estudio_finalizado
                $detalleEstudioFinalizado = new DetalleEstudioFinalizado();
            }

            // Actualizar el detalle_estudio_finalizado con los datos validados
            $detalleEstudioFinalizado->fill($validatedData);

            // Establecer los campos de usuario y fecha
            $detalleEstudioFinalizado->updatedBy = Auth::id();
            $detalleEstudioFinalizado->createdBy = Auth::id();
            $detalleEstudioFinalizado->createdAt = now();
            $detalleEstudioFinalizado->updatedAt = now();// Asegúrate de que el usuario esté autenticado

            // Guardar el detalle_estudio_finalizado
            $detalleEstudioFinalizado->save();

            // Obtener el ID del detalle_estudio_finalizado
            $detalleEstudioFinalizadoId = $detalleEstudioFinalizado->id;

            // Actualizar el registro de Estudio con el id del DetalleEstudioFinalizado
            $estudio->update([
                'detalle_estudio_finalizado_id' => $detalleEstudioFinalizadoId,
                'estado_estudio' => 'finalizado',
            ]);
        }

        //Obtener la posicion mediante una consulta para redireccionar
        $posicion = Estudio::getPosition($nro_servicio);
        $estudios_por_pagina = 20;
        $pagina = ceil($posicion / $estudios_por_pagina);

        
        return redirect()->route('estudios.index', [
            'page' => $pagina,
            'finalizado' => $nro_servicio
        ])->with('success', 'Estudio finalizado con éxito');
    }

    //Metodo reFinally para enviar recibe y tacos

    public function reFinally(Request $request, $nro_servicio)
    {
        // Verifica si el usuario tiene permiso para agregar recibe y tacos al estudio
        if (!auth()->user()->can('estudios.finalizar')) {
            return redirect()->route('estudios.edit', ['nro_servicio' => $nro_servicio])
                ->with('error', 'No tienes permiso para modificar los campos recibe y tacos del estudio.');
        }

        $estudio = Estudio::where('nro_servicio', $nro_servicio)->firstOrFail();
        // Validar los datos entrantes
        $validatedData = $request->validate([
            'recibe' => 'required|string|max:255',
            'tacos' => 'required|string|max:255',
        ]);

        // Obtener el modelo Estudio
        $estudioModel = new Estudio();

        // Obtener el ID del detalle de estudio finalizado usando el nro_servicio
        $detalleEstudioData = $estudioModel->getDetalleFinalizadoId($nro_servicio);

        if (!$detalleEstudioData) {
            return redirect()->back()->with('error', 'No se encontró el detalle del estudio finalizado.');
        }

        // Encontrar el detalle del estudio finalizado por ID
        $detalleEstudio = DetalleEstudioFinalizado::find($detalleEstudioData->id);

        if (!$detalleEstudio) {
            return redirect()->back()->with('error', 'Detalle de estudio finalizado no encontrado.');
        }

        // Actualizar los campos recibe y tacos
        $detalleEstudio->recibe = $validatedData['recibe'];
        $detalleEstudio->tacos = $validatedData['tacos'];
        $detalleEstudio->updatedBy = Auth::id();
        $detalleEstudio->updatedAt = now();
        $detalleEstudio->save();

        $estadoActual = $estudio->estado_estudio;

        if (strpos($estadoActual, 'ampliado') !== false) {
            $nuevoEstado = 'finalizado, ampliado y entregado';
        } else {
            $nuevoEstado = 'finalizado y entregado';
        }

        // Actualizar el estado del estudio
        $estudio->update([
            'estado_estudio' => $nuevoEstado,
        ]);

        // Si el estado es 'finalizado y entregado', revisa si también ha sido ampliado
        /*if ($estudio->estado_estudio === 'finalizado y entregado' && $estudio->detalle_estudio_finalizado) {
            $estudio->update(['estado_estudio' => 'finalizado, entregado y ampliado']);
        }*/


        //Obtener la posicion mediante una consulta para redireccionar
        $posicion = Estudio::getPosition($nro_servicio);
        $estudios_por_pagina = 20;
        $pagina = ceil($posicion / $estudios_por_pagina);

        
        return redirect()->route('estudios.index', [
            'page' => $pagina,
            'finalizado' => $nro_servicio
        ])->with('success', 'Estudio entregado con éxito');
    }

    //Metodo ampliarInforme para enviar la ampliación

    public function ampliarInforme(Request $request, $nro_servicio)
    {
        // Verifica si el usuario tiene permiso para agregar ampliar informe al estudio
        if (!auth()->user()->can('estudios.ampliarInforme')) {
            return redirect()->route('estudios.edit', ['nro_servicio' => $nro_servicio])
                ->with('error', 'No tienes permiso para ampliar informe del estudio.');
        }

        $estudio = Estudio::where('nro_servicio', $nro_servicio)->firstOrFail();

        // Validar los datos entrantes
        $validatedData = $request->validate([
            'informe' => 'nullable|string|max:5000',
        ]);

        // Obtener el modelo Estudio
        $estudioModel = new Estudio();

        // Obtener el ID del detalle de estudio finalizado usando el nro_servicio
        $detalleEstudioData = $estudioModel->getDetalleFinalizadoId($nro_servicio);

        if (!$detalleEstudioData) {
            return redirect()->back()->with('error', 'No se encontró el detalle del estudio finalizado.');
        }

        // Encontrar el detalle del estudio finalizado por ID
        $detalleEstudio = DetalleEstudioFinalizado::find($detalleEstudioData->id);

        if (!$detalleEstudio) {
            return redirect()->back()->with('error', 'Detalle de estudio finalizado no encontrado.');
        }

        // Actualizar los campos ampliar_informe
        $detalleEstudio->ampliar_informe = $validatedData['informe'];
        $detalleEstudio->updatedBy = Auth::id();
        $detalleEstudio->updatedAt = now();
        $detalleEstudio->save();

        // Verificar el estado actual del estudio y actualizarlo
        $estadoActual = $estudio->estado_estudio;
        if (strpos($estadoActual, 'entregado') !== false) {
            $nuevoEstado = 'finalizado, entregado y ampliado';
        } else {
            $nuevoEstado = 'finalizado y ampliado';
        }

        // Actualizar el estudio con el informe adicional
        $estudio->update([
            'estado_estudio' => $nuevoEstado,
            'enviado' => 0,
        ]);

        //Obtener la posicion mediante una consulta para redireccionar
        $posicion = Estudio::getPosition($nro_servicio);
        $estudios_por_pagina = 20;
        $pagina = ceil($posicion / $estudios_por_pagina);

        
        return redirect()->route('estudios.index', [
            'page' => $pagina,
            'finalizado' => $nro_servicio
        ])->with('success', 'Estudio ampliado con éxito');
    }

    public function exportarExcel(Request $request)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames()->toArray();

        $searchNroServicio = $request->input('search_nro_servicio');
        $searchServicio = $request->input('search_servicio');
        $searchTipoEstudio = $request->input('search_tipo_estudio');
        $searchEstado = $request->input('search_estado');
        $searchPaciente = $request->input('search_paciente');
        $searchObraSocial = $request->input('search_obra_social');
        $searchDesde = $request->input('search_desde');
        $searchHasta = $request->input('search_hasta');
        $searchProfesional = $request->input('search_profesional');

        $query = DB::connection('mysql')->table('estudio as e')
            ->select(
                'e.nro_servicio as nro_servicio',
                's.nombre_servicio as servicio',
                'tde.nombre as tipo_estudio',
                'e.estado_estudio as estado',
                DB::raw("CONCAT(p.nombres, ' ', p.apellidos) as paciente"),
                'p.documento as documento',
                'p.obra_social as obra_social',
                'de.diagnostico_presuntivo as diagnostico',
                'e.fecha_carga as fecha_carga',
                DB::raw("CONCAT(prof.nombres, ' ', prof.apellidos) as profesional")
            )
            ->leftJoin('tipo_de_estudio as tde', 'e.tipo_estudio_id', '=', 'tde.id')
            ->leftJoin('servicio as s', 'e.servicio_id', '=', 's.id')
            ->leftJoin('personal as p', 'e.personal_id', '=', 'p.id')
            ->leftJoin('detalle_estudio as de', 'e.detalle_estudio_id', '=', 'de.id')
            ->leftJoin('profesional as prof', 'e.profesional_id', '=', 'prof.id');

        if (in_array('visualizacion', $roles)) {
            $query->where('e.estado_estudio', 'LIKE', '%finalizado%');
        }

        if ($searchNroServicio) {
            $query->where('e.nro_servicio', '=', $searchNroServicio);
        }

        if ($searchServicio) {
            $query->where('s.nombre_servicio', 'LIKE', "%{$searchServicio}%");
        }

        if ($searchObraSocial) {
            $query->where('p.obra_social', 'LIKE', "%{$searchObraSocial}%");
        }

        if ($searchTipoEstudio) {
            $query->where('tde.nombre', 'LIKE', "%{$searchTipoEstudio}%");
        }

        if ($searchEstado) {
            $query->where('e.estado_estudio', 'LIKE', "%{$searchEstado}%");
        }

        if ($searchProfesional) {
            $query->where(function ($q) use ($searchProfesional) {
                $q->whereRaw("CONCAT(prof.nombres, ' ', prof.apellidos) LIKE ?", ["%{$searchProfesional}%"]);
            });
        }

        if ($searchPaciente) {
            $query->where(function ($q) use ($searchPaciente) {
                $q->where(DB::raw("CONCAT(p.nombres, ' ', p.apellidos)"), 'LIKE', "%{$searchPaciente}%")
                    ->orWhere('p.documento', 'LIKE', "%{$searchPaciente}%");
            });
        }

        if ($searchDesde != '' && $searchHasta != '') {
            $query->whereBetween('e.fecha_carga', [$searchDesde, $searchHasta]);
        }

        $query->orderBy('e.nro_servicio', 'desc');

        $estudios = $query->get();

        // Crear un nuevo Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Definir los encabezados de la tabla en negrita
        $sheet->setCellValue('A1', 'N° Serv.')
            ->setCellValue('B1', 'Servicio')
            ->setCellValue('C1', 'Tipo Estudio')
            ->setCellValue('D1', 'Estado')
            ->setCellValue('E1', 'Paciente')
            ->setCellValue('F1', 'DNI')
            ->setCellValue('G1', 'Obra Social')
            ->setCellValue('H1', 'Diagnóstico')
            ->setCellValue('I1', 'Fecha Carga')
            ->setCellValue('J1', 'Profesional');

        // Aplicar estilo en negrita a los encabezados
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        // Agregar los datos a la tabla
        $row = 2;
        foreach ($estudios as $estudio) {
            $sheet->setCellValueExplicit('A' . $row, $estudio->nro_servicio, DataType::TYPE_STRING)
            ->setCellValue('B' . $row, $estudio->servicio)
            ->setCellValue('C' . $row, $estudio->tipo_estudio)
            ->setCellValue('D' . $row, $estudio->estado)
            ->setCellValue('E' . $row, $estudio->paciente)
            ->setCellValueExplicit('F' . $row, $estudio->documento, DataType::TYPE_STRING)
            ->setCellValue('G' . $row, $estudio->obra_social)
            ->setCellValue('H' . $row, $estudio->diagnostico)
            ->setCellValue('I' . $row, \Carbon\Carbon::parse($estudio->fecha_carga)->format('d-m-Y'))
            ->setCellValue('J' . $row, $estudio->profesional);
            // Establecer ajuste de texto en la columna "Diagnóstico"
            $sheet->getStyle('H' . $row)->getAlignment()->setWrapText(true);

            $row++;
        }

        // Autoajustar el ancho de las columnas al contenido excepto "Diagnóstico"
        foreach (range('A', 'J') as $columnID) {
            if ($columnID !== 'H') {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        // Establecer un ancho fijo para la columna "Diagnóstico" (100 caracteres aproximadamente)
        $sheet->getColumnDimension('H')->setWidth(40); // Ajusta este valor según lo necesites

        // Aplicar bordes a todas las celdas con datos
        $sheet->getStyle('A1:J' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Crear el archivo Excel y descargarlo
        $writer = new Xlsx($spreadsheet);
        $fileName = 'estudios_' . date('Ymd_His') . '.xlsx';

        // Configurar las cabeceras para la descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}