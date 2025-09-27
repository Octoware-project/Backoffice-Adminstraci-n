<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadHabitacional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unidades_habitacionales';

    protected $fillable = [
        'numero_departamento',
        'piso',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relación uno a muchos con Persona (una unidad puede tener varios residentes)
    public function personas()
    {
        return $this->hasMany(Persona::class, 'unidad_habitacional_id');
    }

    // Relación uno a muchos con PlanTrabajo
    public function planesTrabajos()
    {
        return $this->hasMany(PlanTrabajo::class, 'unidad_habitacional_id');
    }

    // Scope para filtrar por estado
    public function scopeEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    // Scope para unidades ocupadas (que tienen residentes)
    public function scopeOcupadas($query)
    {
        return $query->whereHas('personas');
    }

    // Scope para unidades disponibles (sin residentes)
    public function scopeDisponibles($query)
    {
        return $query->whereDoesntHave('personas');
    }

    // Accessor para obtener el nombre completo de la unidad
    public function getNombreCompletoAttribute()
    {
        return "Dpto {$this->numero_departamento} - Piso {$this->piso}";
    }

    // Accessor para verificar si está ocupada
    public function getEstaOcupadaAttribute()
    {
        return $this->personas()->count() > 0;
    }
}