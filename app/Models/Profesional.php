<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    use HasFactory;

    protected $table = 'profesional';
    public $timestamps = false;

    protected $fillable = [
        'profesional_salutte_id',
        'nombres',
        'apellidos',
    ];
}