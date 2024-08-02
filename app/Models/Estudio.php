<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'created_by',
    ];

}