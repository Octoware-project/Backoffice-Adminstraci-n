<?php

namespace App\Http\View\Composers;

use App\Models\Persona;
use Illuminate\View\View;

class NavbarComposer
{
    public function compose(View $view)
    {
        // Contar usuarios pendientes
        $usuariosPendientes = Persona::where('estadoRegistro', 'Pendiente')->count();
        
        $view->with('usuariosPendientes', $usuariosPendientes);
    }
}
