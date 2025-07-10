<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCooperativa extends Model
{
    use HasFactory;

    protected $fillable = [
        'ID'
        'Nombre_Completo',
        'Cedula',
        'Celular',
        'Fecha_nacimiento',
        'Correo',
        'Nacionalidad',
        'Estado_civil',
        'Ingresos_totales',
        'Estado',
    ];
}
