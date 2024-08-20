<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estudio extends Model
{

    protected $connection = 'mysql';
    protected $table = 'estudio'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'nro_servicio'; // Asegúrate de que este es el nombre correcto
    public $timestamps = false;
    public $incrementing = true;
    protected $keyType = 'int'; // Cambia esto según el tipo de datos de `nro_servicio`

    protected $fillable = [
        'nro_servicio',
        'estado_estudio',
        'tipo_estudio_id',
        'diagnostico_presuntivo',
        'fecha_carga',
        'medico_solicitante',
        'servicio_id',
        'personal_id',
        'profesional_id',
        'detalle_estudio_id',
        'detalle_pap_id',
        'detalle_estudio_finalizado_id',
        'detalle_pap_finalizado_id',
        'enviado',
        'createdBy',
        'updatedBy',
    ];

    public function getDetalleFinalizadoId($nro_servicio)
    {
        return DB::table('estudio as e')
            ->join('detalle_estudio_finalizado as def', 'e.detalle_estudio_finalizado_id', '=', 'def.id')
            ->select('e.nro_servicio', 'def.id')
            ->where('e.nro_servicio', $nro_servicio)
            ->first();  // Usa `first()` si esperas solo un resultado; usa `get()` si esperas múltiples resultados
    }


    public static function getPosition($nro_servicio)
    {
        return DB::table('estudio')
            ->selectRaw('COUNT(*) + 1 AS posicion')
            ->where('nro_servicio', '>', $nro_servicio)
            ->value('posicion');
    }

}