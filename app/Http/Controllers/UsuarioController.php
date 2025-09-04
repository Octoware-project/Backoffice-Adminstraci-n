<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index()
    {
        $pendientes = Persona::where('estadoRegistro', 'Pendiente')->with('user')->get();
        $aceptados  = Persona::where('estadoRegistro', 'Aceptado')->with('user')->get();
        $rechazados = Persona::where('estadoRegistro', 'Rechazado')->with('user')->get();

        return view('admin.usuarios.index', compact('pendientes', 'aceptados', 'rechazados'));
    }

    public function aceptar($id)
    {
        $usuario = Persona::findOrFail($id);
        if ($usuario->estadoRegistro === 'Pendiente') {
            $usuario->estadoRegistro = 'Aceptado';
            $usuario->save();

            // Generar contraseña aleatoria
            $password = Str::random(10);

            // Asignar contraseña al usuario relacionado
            if ($usuario->user) {
                $usuario->user->password = Hash::make($password);
                $usuario->user->save();
            }

            // Mostrar la contraseña en la vista show
            return redirect()->route('usuarios.show', ['id' => $usuario->id, 'password' => $password]);
        }
        return redirect()->route('usuarios.index');
    }

    public function rechazar($id)
    {
        $usuario = Persona::findOrFail($id);
        if ($usuario->estadoRegistro === 'Pendiente') {
            $usuario->estadoRegistro = 'Rechazado';
            $usuario->save();
        }
        return redirect()->route('usuarios.index');
    }

    public function show($id)
    {
        $usuario = Persona::with('user')->findOrFail($id);
        $password = request()->query('password'); // Recibe la contraseña si viene de aceptar
        return view('admin.usuarios.show', compact('usuario', 'password'));
    }

    public function edit($id)
    {
        $usuario = Persona::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Persona::findOrFail($id);

        // Ajusta según los nombres reales de las columnas en la tabla personas
        $usuario->name = $request->nombre; // Si la columna es 'name'
        $usuario->apellido = $request->apellido;
        $usuario->CI = $request->CI;
        $usuario->Telefono = $request->Telefono;
        $usuario->Direccion = $request->Direccion;
        $usuario->Estado_Registro = $request->Estado_Registro;
        $usuario->save();

        if ($usuario->user) {
            $usuario->user->email = $request->email;
            $usuario->user->save();
        }

        return redirect()->route('usuarios.show', $usuario->id)->with('success', 'Datos actualizados correctamente.');
    }
}
