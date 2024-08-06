<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePap extends Model
{

    protected $connection = 'mysql';
    protected $table = 'detalle_pap'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id'; // Asegúrate de que este es el nombre correcto
    public $timestamps = true; // Habilitar timestamps

    const UPDATED_AT = 'updatedAt';
    const CREATED_AT = null;

    protected $fillable = [
        'estado_especimen',
        'celulas_pavimentosas',
        'celulas_cilindricas',
        'valor_hormonal',
        'fecha_lectura',
        'valor_hormonal_HC',
        'cambios_reactivos',
        'cambios_asoc_celula_pavimentosa',
        'cambios_celula_glandulares',
        'celula_metaplastica',
        'otras_neo_malignas',
        'toma',
        'recomendaciones',
        'microorganismos',
        'resultado',
        'updatedBy',
        'updatedAt',
    ];

}