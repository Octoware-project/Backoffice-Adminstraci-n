<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Horas_Mensuales extends Model
{
    protected $table = 'horas_mensuales';
    protected $fillable = [
        'email', 'anio', 'mes', 'Semana', 'Cantidad_Horas', 'Motivo_Falla', 'Tipo_Justificacion', 'Monto_Compensario'
    ];
}
