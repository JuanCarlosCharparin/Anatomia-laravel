<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class Paciente extends Model
{
    // Usa la conexión por defecto (db-sistema-ap)
    protected $connection = 'db2'; // Para la base de datos 'db-sistema-ap'
    protected $table = 'persona'; // Nombre de la tabla en la base de datos


    protected $fillable = [
        'id',
        'documento',
        'apellidos',
        'nombres',
        'fecha_nacimiento',
        'genero',
    ];

    //Obtener Pacientes


    /*public static function search($searchTerm)
    {
        return self::select(
                            'persona.id as id',
                            'persona.documento as documento',
                            'persona.nombres as nombres',
                            'persona.apellidos as apellidos',
                            DB::raw("
                                CASE 
                                    WHEN persona.genero = 'm' THEN 'Masculino'
                                    WHEN persona.genero = 'f' THEN 'Femenino'
                                    ELSE 'Desconocido'
                                END AS genero
                            "),
                            'persona.fecha_nacimiento as fecha_nacimiento',
                            DB::raw('TIMESTAMPDIFF(YEAR, persona.fecha_nacimiento, CURDATE()) AS edad'),
                            'obra_social.nombre as obra_social',
                            'persona.contacto_email_direccion as email',
                            DB::raw("
                                CASE 
                                    WHEN COALESCE(persona.contacto_telefono_codigo, '') = '' AND COALESCE(persona.contacto_telefono_numero, '') = '' 
                                    THEN 'No proporcionado'
                                    ELSE CONCAT(COALESCE(persona.contacto_telefono_codigo, ''), ' ', COALESCE(persona.contacto_telefono_numero, ''))
                                END AS contacto_telefono
                            "),
                            DB::raw("
                                CASE 
                                    WHEN COALESCE(persona.contacto_celular_prefijo, '') = '' AND COALESCE(persona.contacto_celular_numero, '') = '' 
                                    THEN 'No proporcionado'
                                    ELSE CONCAT(COALESCE(persona.contacto_celular_prefijo, ''), ' ', COALESCE(persona.contacto_celular_codigo, ''), ' ', COALESCE(persona.contacto_celular_numero, ''))
                                END AS contacto_telefono_2
                            ")
        )
        ->join('persona_plan as pp', 'persona.id', '=', 'pp.persona_id')
        ->join('plan as pl', 'pp.plan_id', '=', 'pl.id')
        ->join('obra_social', 'pl.obra_social_id', '=', 'obra_social.id')
        ->join('persona_plan_por_defecto as pppd', 'pp.id', '=', 'pppd.persona_plan_id')
        ->where('persona.documento', 'LIKE', "%{$searchTerm}%")
        ->orWhere(DB::raw('CONCAT(persona.nombres, " ", persona.apellidos)'), 'LIKE', "%{$searchTerm}%")
        ->get();
    }*/

    /*public static function findByDni($dni)
    {
        return self::select(
                'persona.id as id',
                'persona.documento as documento',
                'persona.nombres as nombres',
                'persona.apellidos as apellidos',
                'persona.fecha_nacimiento as fecha_nacimiento',
                'persona.genero as genero',
                'obra_social.nombre as obra_social')
            ->join('persona_plan as pp', 'persona.id', '=', 'pp.persona_id')
            ->join('plan as pl', 'pp.plan_id', '=', 'pl.id')
            ->join('obra_social as obra_social', 'pl.obra_social_id', '=', 'obra_social.id')
            ->join('persona_plan_por_defecto as pppd', 'pp.id', '=', 'pppd.persona_plan_id')
            ->where('persona.documento', $dni) // Ajustado para buscar por DNI exacto
            ->first(); // Retorna un solo resultado
    }*/

    public static function findByDni($dni)
    {
        if (empty($dni)) {
            return response()->json(['error' => 'Debe proporcionar un DNI'], 400);
        }

        try {
            // Realizar la solicitud a la API usando Http (Laravel HTTP Client)
            $response = Http::get("http://172.22.118.101:81/apialephoo/public/api/v1/personas/{$dni}");

            // Verificar si la solicitud falló
            if ($response->failed()) {
                return response()->json(['error' => 'No se pudo obtener información del paciente'], 500);
            }

            // Decodificar los datos de la API
            $apiData = $response->json();

            // Transformar los datos al formato esperado
            $transformedData = [
                'id' => $apiData['id'],
                'documento' => $apiData['documento'],
                'nombres' => $apiData['nombre'], // Cambiado para coincidir con tu ejemplo
                'apellidos' => $apiData['apellidos'],
                'fecha_nacimiento' => $apiData['fecha_nacimiento'],
                'edad' => self::calculateAge($apiData['fecha_nacimiento'] ?? null), // Llamada a función para calcular la edad
                'genero' => $apiData['genero'],
                'obra_social' => $apiData['obra_social'] ?? 'No disponible', // Agregar valor predeterminado si no existe
                'email' => $apiData['email'] ?? '',
                'contacto_telefono' => $apiData['telefono_celular'] ?? '',
                'contacto_telefono_2' => $apiData['telefono_fijo'] ?? '',
            ];

            return $transformedData;
        } catch (\Exception $e) {
            // Manejar cualquier excepción y devolver un error genérico
            \Log::error('Error al buscar por DNI en la API: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud'], 500);
        }
    }

    public static function calculateAge($fechaNacimiento)
    {
        if (!$fechaNacimiento) {
            return null;
        }

        $birthDate = \Carbon\Carbon::parse($fechaNacimiento);
        return $birthDate->age;
    }



    //Obtener Profesionales

    public static function getProfessionals()
    {
        $professionalIds = [198041, 106780, 216265];
        
        return self::select(
                'persona.id as profesional_id', // Cambia 'id' a 'persona.id' para evitar ambigüedad
                'persona.nombres',
                'persona.apellidos'
            )
            ->from('persona') // Asegúrate de establecer la tabla base
            ->join('personal', 'personal.persona_id', '=', 'persona.id')
            ->join('asignacion', 'personal.id', '=', 'asignacion.personal_id')
            ->join('especialidad', 'asignacion.especialidad_id', '=', 'especialidad.id')
            ->whereIn('persona.id', $professionalIds)
            ->distinct()
            ->get();
    }

    public static function findEmail($persona_id)
    {
        return DB::connection('db2')
            ->table('persona')
            ->select(
                'persona.id as id',
                'persona.contacto_email_direccion as email',
                'persona.documento as documento',
                DB::raw("CONCAT(persona.contacto_telefono_codigo, ' ', persona.contacto_telefono_numero) AS contacto_telefono")
            )
            ->where('persona.id', $persona_id)
            ->first(); // Retorna un solo resultado como un objeto estándar
    }


}