<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'personas';

    protected $fillable = [
        'name',
        'apellido',
        'CI',
        'telefono',
        'direccion',
        'estadoCivil',
        'genero', 
        'fechaNacimiento',
        'ocupacion',
        'nacionalidad',
        'estadoRegistro',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
