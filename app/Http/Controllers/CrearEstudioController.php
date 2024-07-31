<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\Paciente;
use App\Models\Personal; 
use App\Models\Profesional;
use App\Models\Especialidad;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importa la fachada DB

class CrearEstudioController extends Controller
{
    public function index(Request $request)
    {
        $searchGeneral = $request->input('search_general');
        $searchNroServicio = $request->input('search_nro_servicio');

        $query = DB::connection('mysql')->table('estudio as e')
            ->select(
                'e.nro_servicio as nro_servicio',
                's.nombre_servicio as servicio',
                'tde.nombre as tipo_estudio',
                'e.estado_estudio as estado',
                DB::raw("CONCAT(p.nombres, ' ', p.apellidos) as paciente"),
                'p.obra_social as obra_social',
                'de.diagnostico_presuntivo as diagnostico',
                'e.fecha_carga as fecha_carga',
                DB::raw("CONCAT(prof.nombres, ' ', prof.apellidos) as profesional")
            )
            ->leftJoin('tipo_de_estudio as tde', 'e.tipo_estudio_id', '=', 'tde.id')
            ->leftJoin('servicio as s', 'e.servicio_id', '=', 's.id')
            ->leftJoin('personal as p', 'e.personal_id', '=', 'p.id')
            ->leftJoin('detalle_estudio as de', 'e.detalle_estudio_id', '=', 'de.id')
            ->leftJoin('profesional as prof', 'e.profesional_id', '=', 'prof.id')
            ->orderBy('e.nro_servicio');

        // Aplicar filtro de búsqueda general
        if ($searchGeneral) {
            $query->where(function ($q) use ($searchGeneral) {
                $q->where('s.nombre_servicio', 'LIKE', "%{$searchGeneral}%")
                ->orWhere('tde.nombre', 'LIKE', "%{$searchGeneral}%")
                ->orWhere(DB::raw("CONCAT(p.nombres, ' ', p.apellidos)"), 'LIKE', "%{$searchGeneral}%")
                ->orWhere('p.obra_social', 'LIKE', "%{$searchGeneral}%")
                ->orWhere('de.diagnostico_presuntivo', 'LIKE', "%{$searchGeneral}%")
                ->orWhere('e.fecha_carga', 'LIKE', "%{$searchGeneral}%")
                ->orWhere(DB::raw("CONCAT(prof.nombres, ' ', prof.apellidos)"), 'LIKE', "%{$searchGeneral}%")
                ->orWhere('e.estado_estudio', 'LIKE', "%{$searchGeneral}%");
            });
        }

        // Aplicar búsqueda por número de servicio
        if ($searchNroServicio) {
            $query->where('e.nro_servicio', 'LIKE', "%{$searchNroServicio}%");
        }

        // Ejecuta la consulta y pagina los resultados
        $estudios = $query->paginate(20);

        return view('estudios.index', compact('estudios', 'searchGeneral', 'searchNroServicio'));
    }

    public function create()
    {
        // Obtener solo el valor del campo nro_servicio más alto
        $lastEstudio = Estudio::latest('nro_servicio')->first();

        // Generar el nuevo número de servicio
        $newServicioNumber = $lastEstudio ? $lastEstudio->nro_servicio + 1 : 1;

        $professionals = Paciente::getProfessionals();
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
            'diagnostico' => 'nullable|string|max:255',
            'fecha_carga' => 'required|date',
            'medico_solicitante' => 'nullable|string|max:255',
            'servicio_salutte_id' => ['required', function ($attribute, $value, $fail) {
                // Verificar existencia en db2
                $servicio = Especialidad::where('id', $value)->first();
                if (!$servicio) {
                    $fail('El servicio no existe en la base de datos.');
                }
            }],
            'documento' => ['required', function ($attribute, $value, $fail) {
                // Verificar existencia en db2
                $paciente = Paciente::findByDni($value);
                if (!$paciente) {
                    $fail('El paciente no existe en la base de datos.');
                }
            }],
            'profesional_salutte_id' => 'required|exists:profesional,profesional_salutte_id',
        ]);

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
            ['documento' => $paciente->documento], // Usa el DNI como identificador
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

        // Crear el nuevo estudio y usar el ID del servicio
        $estudio = Estudio::create([
            'nro_servicio' => $validatedData['nro_servicio'],
            'estado_estudio' => 'creado',
            'tipo_estudio_id' => $validatedData['tipo_estudio'],
            'diagnostico_presuntivo' => $validatedData['diagnostico'],
            'fecha_carga' => $validatedData['fecha_carga'],
            'medico_solicitante' => $validatedData['medico_solicitante'],
            'servicio_id' => $servicioId,
            'personal_id' => $pacienteId,
            'profesional_id' => $profesionalId,
        ]);

        // Redirigir o devolver respuesta
        return redirect()->route('estudios.index')->with('success', 'Estudio creado exitosamente.');
    }

    // Función para buscar paciente
    public function searchPatient(Request $request)
    {
        $searchTerm = $request->input('search');

        // Llama al método search del modelo Paciente
        $patients = Paciente::search($searchTerm);

        return response()->json($patients);
    }

    // Función para buscar profesional
    public function searchProfessional(Request $request)
    {
        // Puedes cambiar estos IDs o obtenerlos dinámicamente según tus necesidades
        $professionalIds = [198041, 106780]; 
    
        // Consultar profesionales usando el modelo Paciente
        $professionals = Paciente::searchProfessional($professionalIds);
    
        // Asegúrate de que 'professionals' es una variable que existe en la vista
        return view('estudios.create', compact('professionals'));
    }
}
