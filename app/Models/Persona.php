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
        'user_id',
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
        'fecha_aceptacion',
        'unidad_habitacional_id',
        'fecha_asignacion_unidad',
    ];

    protected $casts = [
        'fechaNacimiento' => 'date',
        'fecha_aceptacion' => 'datetime',
        'fecha_asignacion_unidad' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con Unidad Habitacional
    public function unidadHabitacional()
    {
        return $this->belongsTo(UnidadHabitacional::class, 'unidad_habitacional_id');
    }

    // Scope para personas con unidad asignada
    public function scopeConUnidad($query)
    {
        return $query->whereNotNull('unidad_habitacional_id');
    }

    // Scope para personas sin unidad asignada
    public function scopeSinUnidad($query)
    {
        return $query->whereNull('unidad_habitacional_id');
    }
}
