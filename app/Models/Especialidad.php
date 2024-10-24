<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Especialidad extends Model
{
    
    protected $connection = 'db2'; // Para la base de datos 'db_salutte'
    protected $table = 'especialidad'; // Nombre de la tabla en la base de datos


    protected $fillable = [
        'nombre_servicio',
        'servicio_salutte_id' // Agrega otros campos si es necesario
    ];


    public static function getServicio()
    {
        // Lista de ID de departamento para el filtro
        $departamentoIds = [6661180, 6661192, 6661178, 6661181, 6661162];
        $especialidadIds = [6661573]; 
        
        return self::select(
                'especialidad.id as servicio_salutte_id',
                'especialidad.nombre as nombre_servicio',
                'especialidad.departamento_id'
            )
            ->distinct()
            ->join('departamento as d', 'd.id', '=', 'especialidad.departamento_id')
            ->whereIn('especialidad.departamento_id', $departamentoIds)
            ->orWhereIn('especialidad.id', $especialidadIds)
            ->orderBy('especialidad.nombre', 'asc')
            ->get();
    }

    public static function findServicioById($servicioSalutteId)
    {
        return self::select(
                'especialidad.id as servicio_salutte_id',
                'especialidad.nombre as nombre_servicio'
            )
            ->where('especialidad.id', $servicioSalutteId)
            ->first();
    }

}