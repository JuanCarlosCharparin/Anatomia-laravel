<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoNomencladorAP extends Model
{
    use HasFactory;

    protected $table = 'codigo_nomenclador_ap';
    public $timestamps = false;

    protected $fillable = [
        'nro_servicio',
        'codigo',
    ];

    public $timestamps = false; // Si no usas timestamps en esta tabla
}
