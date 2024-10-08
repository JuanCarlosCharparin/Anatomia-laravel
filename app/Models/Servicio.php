<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    
    use HasFactory;
    
    protected $table = 'servicio';
    public $timestamps = false;

    protected $fillable = [
        'nombre_servicio',
        'servicio_salutte_id',
    ];
}
