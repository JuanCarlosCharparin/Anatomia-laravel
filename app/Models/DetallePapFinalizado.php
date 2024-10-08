<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePapFinalizado extends Model
{

    protected $connection = 'mysql';
    protected $table = 'detalle_pap_finalizado'; 
    protected $primaryKey = 'id'; // Asegúrate de que este es el nombre correcto
    public $timestamps = false;

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
        'createdBy',
        'createdAt',
    ];

    // Define los campos de fecha para la conversión de fechas
    protected $dates = [
        'createdAt',
    ];

}