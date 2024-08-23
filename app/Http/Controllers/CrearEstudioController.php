<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\Paciente;
use App\Models\Personal;
use App\Models\Profesional;
use App\Models\Especialidad;
use App\Models\Servicio;
use App\Models\CodigoNomencladorAP;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception; // Asegúrate de importar Exception

class CrearEstudioController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (auth()->check()) {
            $roles = $user->getRoleNames()->toArray();
            // Continúa con la lógica adicional
        } else {
            return redirect()->route('login');
        }

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
                'p.documento as documento', // Alias correcto
                'p.obra_social as obra_social',
                'de.diagnostico_presuntivo as diagnostico',
                'e.fecha_carga as fecha_carga',
                'e.enviado as enviado',
                DB::raw("CONCAT(prof.nombres, ' ', prof.apellidos) as profesional")
            )
            ->leftJoin('tipo_de_estudio as tde', 'e.tipo_estudio_id', '=', 'tde.id')
            ->leftJoin('servicio as s', 'e.servicio_id', '=', 's.id')
            ->leftJoin('personal as p', 'e.personal_id', '=', 'p.id')
            ->leftJoin('detalle_estudio as de', 'e.detalle_estudio_id', '=', 'de.id')
            ->leftJoin('profesional as prof', 'e.profesional_id', '=', 'prof.id');

        // Si el usuario tiene el rol 'visualizacion', agregar filtro para los estados permitidos
        if (in_array('visualizacion', $roles)) {
            $query->where('e.estado_estudio', 'LIKE', '%finalizado%');
        }

        // Aplicar filtro de búsqueda general

        // Aplicar búsqueda por número de servicio
        if ($searchNroServicio) {
            $query->where('e.nro_servicio', '=', $searchNroServicio);
        }

        if ($searchServicio) {
            $query->where('s.nombre_servicio', 'LIKE', "%{$searchServicio}%");
        }

        if ($searchTipoEstudio) {
            $query->where('tde.nombre', 'LIKE', "%{$searchTipoEstudio}%");
        }

        if ($searchEstado) {
            $query->where('e.estado_estudio', 'LIKE', "%{$searchEstado}%");
        }

        if ($searchObraSocial) {
            $query->where('p.obra_social', 'LIKE', "%{$searchObraSocial}%");
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






        // Ordenar los resultados por nro_servicio
        $query->orderBy('e.nro_servicio', 'desc');

        // Ejecuta la consulta y pagina los resultados
        $estudios = $query->paginate(20);

        return view('estudios.index', compact(
            'estudios',
            'searchServicio',
            'searchNroServicio',
            'searchTipoEstudio',
            'searchEstado',
            'searchPaciente',
            'searchObraSocial',
            'searchDesde',
            'searchHasta',
            'searchProfesional'
        )
        );
    }

    public function create()
    {

        // Verifica si el usuario tiene permiso para crear estudios
        if (!auth()->user()->can('estudios.create')) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para crear estudios.');
        }

        // Obtener solo el valor del campo nro_servicio más alto
        $lastEstudio = Estudio::latest('nro_servicio')->first();

        // Generar el nuevo número de servicio
        $newServicioNumber = $lastEstudio ? $lastEstudio->nro_servicio + 1 : 1;

        $professionals = Paciente::getProfessionals(); // Asumí que obtienes todos los profesionales aquí
        $servicios = Especialidad::getServicio();

        // Pasar los datos a la vista
        return view('estudios.create', compact('professionals', 'newServicioNumber', 'servicios'));
    }

    public function store(Request $request)
    {
        // Validación de datos
        $validatedData = $request->validate([
            'nro_servicio' => 'required|string|max:255',
            'tipo_estudio' => 'required|exists:tipo_de_estudio,id',
            'diagnostico' => 'nullable|string|max:500',
            'fecha_carga' => 'required|date',
            'medico_solicitante' => 'nullable|string|max:255',
            'servicio_salutte_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Verificar existencia en db2
                    $servicio = Especialidad::where('id', $value)->first();
                    if (!$servicio) {
                        $fail('El servicio no existe en la base de datos.');
                    }
                }
            ],
            'documento' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Verificar existencia en db2
                    $paciente = Paciente::findByDni($value);
                    if (!$paciente) {
                        $fail('El paciente no existe en la base de datos.');
                    }
                }
            ],
            'profesional_salutte_id' => 'required|exists:profesional,profesional_salutte_id',
            'codigos' => 'nullable|array',
            'codigos.*' => 'nullable|string|max:255',
            'materiales' => 'nullable|array',
            'materiales.*' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();

        try {
            // Obtener el ID del servicio desde la base de datos secundaria
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

            // Obtener el DNI del paciente desde la base de datos secundaria
            $documento = $validatedData['documento'];
            $paciente = Paciente::findByDni($documento);

            // Crear o actualizar el paciente en la base de datos principal
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

            // Obtener el ID del paciente en la base de datos principal
            $pacienteId = $pacienteDb->id;

            // Verificar el ID del profesional en la base de datos principal
            $profesionalSalutteId = $validatedData['profesional_salutte_id'];
            $profesional = Profesional::where('profesional_salutte_id', $profesionalSalutteId)->first();

            if (!$profesional) {
                return redirect()->back()->with('error', 'El profesional seleccionado no existe en la base de datos.');
            }

            $profesionalId = $profesional->id;

            // Crear o actualizar el estudio
            Estudio::updateOrCreate([
                'nro_servicio' => $validatedData['nro_servicio'],
                'estado_estudio' => 'creado',
                'tipo_estudio_id' => $validatedData['tipo_estudio'],
                'diagnostico_presuntivo' => $validatedData['diagnostico'],
                'fecha_carga' => $validatedData['fecha_carga'],
                'medico_solicitante' => $validatedData['medico_solicitante'],
                'servicio_id' => $servicioId,
                'personal_id' => $pacienteId,
                'profesional_id' => $profesionalId,
                'createdBy' => Auth::id(),
            ]);

            // Agregar códigos a la tabla codigo_nomenclador_ap
            $codigosNomenclador = $validatedData['codigos'] ?? [];

            if (!empty($codigosNomenclador)) {
                $codigosToInsert = array_filter($codigosNomenclador, fn($codigo) => !empty ($codigo));
                if (!empty($codigosToInsert)) {
                    CodigoNomencladorAP::upsert(
                        array_map(fn($codigo) => ['nro_servicio' => $validatedData['nro_servicio'], 'codigo' => $codigo], $codigosToInsert),
                        ['nro_servicio', 'codigo']
                    );
                }
            }

            // Agregar materiales a la tabla material
            $materiales = $validatedData['materiales'] ?? [];

            if (!empty($materiales)) {
                $materialesToInsert = array_filter($materiales, fn($material) => !empty ($material));
                if (!empty($materialesToInsert)) {
                    Material::upsert(
                        array_map(fn($material) => ['nro_servicio' => $validatedData['nro_servicio'], 'material' => $material], $materialesToInsert),
                        ['nro_servicio', 'material']
                    );
                }
            }

            DB::commit();

            $userName = Auth::user()->name;
            $nro_servicio = $validatedData['nro_servicio'];
            //Obtener la posicion mediante una consulta para redireccionar
            $posicion = Estudio::getPosition($nro_servicio);
            $estudios_por_pagina = 20;
            $pagina = ceil($posicion / $estudios_por_pagina);

            
            return redirect()->route('estudios.index', [
                'page' => $pagina,
                'finalizado' => $nro_servicio
            ])->with('success', 'Estudio creado exitosamente por ' . $userName . '.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('estudios.index')->with('error', 'Ocurrió un error al crear el estudio: ' . $e->getMessage());
        }
    }

    // Función para buscar paciente
    public function searchPatient(Request $request)
    {
        $searchTerm = $request->input('search');

        // Llama al método search del modelo Paciente
        $patients = Paciente::search($searchTerm);

        return response()->json($patients);
    }
}