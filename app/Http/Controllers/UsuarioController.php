<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class UsuarioController extends Controller
{
    public function index()
    {
        $pendientes = Persona::where('Estado_Registro', 'Pendiente')->get();
        $aceptados  = Persona::where('Estado_Registro', 'Aceptado')->get();
        $rechazados = Persona::where('Estado_Registro', 'Rechazado')->get();

        return view('admin.usuarios.index', compact('pendientes', 'aceptados', 'rechazados'));
    }

    public function aceptar($id)
    {
        $usuario = Persona::findOrFail($id);
        if ($usuario->Estado_Registro === 'Pendiente') {
            $usuario->Estado_Registro = 'Aceptado';
            $usuario->save();
        }
        return redirect()->route('usuarios.index');
    }

    public function rechazar($id)
    {
        $usuario = Persona::findOrFail($id);
        if ($usuario->Estado_Registro === 'Pendiente') {
            $usuario->Estado_Registro = 'Rechazado';
            $usuario->save();
        }
        return redirect()->route('usuarios.index');
    }
}
