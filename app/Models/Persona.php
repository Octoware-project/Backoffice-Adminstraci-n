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
        "CI",
        "telefono", // Usar nombre correcto de la migración
        "direccion", // Usar nombre correcto de la migración
        "estadoRegistro", // Usar nombre correcto de la migración
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
