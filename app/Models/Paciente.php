<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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


    public static function search($searchTerm)
    {
        return self::select(
                            'persona.id as id',
                            'persona.documento as documento',
                            'persona.nombres as nombres',
                            'persona.apellidos as apellidos',
                            'persona.genero as genero',
                            'persona.fecha_nacimiento as fecha_nacimiento',
                            'obra_social.nombre as obra_social',
                            'persona.contacto_email_direccion as email')
            ->join('persona_plan as pp', 'persona.id', '=', 'pp.persona_id')
            ->join('plan as pl', 'pp.plan_id', '=', 'pl.id')
            ->join('obra_social as obra_social', 'pl.obra_social_id', '=', 'obra_social.id')
            ->join('persona_plan_por_defecto as pppd', 'pp.id', '=', 'pppd.persona_plan_id')
            ->where('persona.documento', 'LIKE', "%{$searchTerm}%")
            ->orWhere(DB::raw('CONCAT(persona.nombres, " ", persona.apellidos)'), 'LIKE', "%{$searchTerm}%")
            ->get();
    }

    public static function findByDni($dni)
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
    }



    //Obtener Profesionales

    public static function getProfessionals()
    {
        $professionalIds = [198041, 106780];
        
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



    public static function findById($id)
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
            ->where('persona.id', $id) // Ajustado para buscar por DNI exacto
            ->first(); // Retorna un solo resultado
    }


}