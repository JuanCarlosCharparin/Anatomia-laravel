<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}