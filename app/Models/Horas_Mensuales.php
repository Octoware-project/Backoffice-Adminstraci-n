<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horas_Mensuales extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'horas_mensuales';
    protected $fillable = [
        'email', 'anio', 'mes', 'Semana', 'Cantidad_Horas', 'Motivo_Falla', 'Tipo_Justificacion', 'Monto_Compensario'
    ];
}
