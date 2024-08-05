<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEstudioFinalizado extends Model
{

    protected $connection = 'mysql';
    protected $table = 'detalle_estudio_finalizado'; 
    protected $primaryKey = 'id'; // Asegúrate de que este es el nombre correcto
    public $timestamps = false;

    protected $fillable = [
        'macro',
        'fecha_macro',
        'micro',
        'fecha_inclusion',
        'fecha_corte',
        'fecha_entrega',
        'observacion',
        'maligno',
        'observacion_interna',
        'recibe',
        'tacos',
        'ampliar_informe',
        'diagnostico_presuntivo',
        'tecnicas',
        'createdBy',
        'createdAt',
    ];

}