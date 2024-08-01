<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEstudio extends Model
{

    protected $connection = 'mysql';
    protected $table = 'detalle_estudio'; 
    public $timestamps = false;

    protected $fillable = [
        'macro',
        'fecha_macro',
        'micro',
        'fecha_micro',
        'conclusion',
        'observacion',
        'maligno',
        'guardado',
        'observacion_interna',
        'recibe',
        'tacos',
        'diagnostico_presuntivo',
        'tecnicas',
        'createdBy',
        'createdAt',
        'updatedBy',
        'updatedAt',
    ];

}