<?php

namespace App\Http\Controllers;

use App\Models\Persona;

abstract class Controller
{

    protected function getUsuariosPendientes()
    {
        try {
            return Persona::where('estadoRegistro', 'Pendiente')->count();
        } catch (\Exception $e) {
            \Log::error('Error al obtener usuarios pendientes: ' . $e->getMessage());
            return 0;
        }
    }
}
