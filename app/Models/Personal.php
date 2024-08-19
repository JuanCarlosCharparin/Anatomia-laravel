<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Importar DB para consultas SQL

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';
    public $timestamps = false;

    protected $fillable = [
        'persona_salutte_id',
        'nombres',
        'apellidos',
        'obra_social',
        'fecha_nacimiento',
        'documento',
        'genero',
    ];

    /**
     * Obtener los datos del estudio y paciente por nro_servicio.
     *
     * @param int $nro_servicio
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getEstudioPaciente($nro_servicio)
    {
        return DB::table('estudio as e')
            ->leftJoin('personal as p', 'e.personal_id', '=', 'p.id')
            ->select('e.nro_servicio', 'p.persona_salutte_id', DB::raw("CONCAT(p.nombres, ' ', p.apellidos) AS paciente"))
            ->where('e.nro_servicio', $nro_servicio)
            ->first(); 
    }
}
