<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'material';

    // Si no usas timestamps en esta tabla
    public $timestamps = false; 

    protected $fillable = [
        'nro_servicio',
        'material',
    ];

    
}