<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        // Esta será la página de usuarios aceptados solamente
        return $this->usuariosAceptados($request);
    }

    public function pendientes(Request $request)
    {
        // Página específica para usuarios pendientes
        $query = Persona::with('user')->where('estadoRegistro', 'Pendiente');

        // Filtro por nombre (busca en name y apellido)
        if ($request->filled('filter_nombre')) {
            $searchTerm = $request->filter_nombre;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apellido', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(name, ' ', apellido) LIKE ?", ["%{$searchTerm}%"]);
            });
        }

        // Filtro por email
        if ($request->filled('filter_email')) {
            $searchEmail = $request->filter_email;
            $query->whereHas('user', function($q) use ($searchEmail) {
                $q->where('email', 'LIKE', "%{$searchEmail}%");
            });
        }

        // Aplicar ordenamiento
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Validar campo de ordenamiento
        $allowedSortFields = ['created_at', 'name', 'email'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // Validar dirección de ordenamiento
        $allowedDirections = ['asc', 'desc'];
        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'desc';
        }

        // Aplicar ordenamiento según el campo seleccionado
        switch ($sortField) {
            case 'name':
                $query->orderByRaw("CONCAT(name, ' ', apellido) {$sortDirection}");
                break;
            case 'email':
                $query->leftJoin('users', 'personas.user_id', '=', 'users.id')
                      ->orderBy('users.email', $sortDirection)
                      ->select('personas.*');
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $sortDirection);
                break;
        }

        $usuarios = $query->get();

        return view('admin.usuarios.pendientes', compact('usuarios'));
    }

    public function usuariosAceptados(Request $request)
    {
        // Construir query base con relaciones para usuarios aceptados e inactivos
        $query = Persona::with('user')->whereIn('estadoRegistro', ['Aceptado', 'Inactivo']);

        // Filtro por nombre (busca en name y apellido)
        if ($request->filled('filter_nombre')) {
            $searchTerm = $request->filter_nombre;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apellido', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(name, ' ', apellido) LIKE ?", ["%{$searchTerm}%"]);
            });
        }

        // Filtro por email
        if ($request->filled('filter_email')) {
            $searchEmail = $request->filter_email;
            $query->whereHas('user', function($q) use ($searchEmail) {
                $q->where('email', 'LIKE', "%{$searchEmail}%");
            });
        }

        // Aplicar ordenamiento
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Validar campo de ordenamiento
        $allowedSortFields = ['created_at', 'name', 'email'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // Validar dirección de ordenamiento
        $allowedDirections = ['asc', 'desc'];
        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'desc';
        }

        // Aplicar ordenamiento según el campo seleccionado
        switch ($sortField) {
            case 'name':
                $query->orderByRaw("CONCAT(name, ' ', apellido) {$sortDirection}");
                break;
            case 'email':
                $query->leftJoin('users', 'personas.user_id', '=', 'users.id')
                      ->orderBy('users.email', $sortDirection)
                      ->select('personas.*');
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', $sortDirection);
                break;
        }

        $usuarios = $query->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function aceptar(Request $request, $id)
    {
        $usuario = Persona::findOrFail($id);
        
        if ($usuario->estadoRegistro === 'Pendiente') {
            $usuario->estadoRegistro = 'Inactivo';
            $usuario->save();
            
            // Si es una petición AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Usuario {$usuario->name} {$usuario->apellido} aceptado correctamente (estado: Inactivo)"
                ]);
            }
            
            return redirect()->route('usuarios.show', $id)
                           ->with('success', "Usuario {$usuario->name} {$usuario->apellido} aceptado correctamente (estado: Inactivo)");
        }
        
        // Si es una petición AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario ya no está pendiente de aprobación'
            ]);
        }
        
        return redirect()->route('usuarios.pendientes')
                       ->with('error', 'El usuario ya no está pendiente de aprobación');
    }

    public function rechazar(Request $request, $id)
    {
        $usuario = Persona::findOrFail($id);
        
        if ($usuario->estadoRegistro === 'Pendiente') {
            $usuario->estadoRegistro = 'Rechazado';
            $usuario->save();
            
            // Si es una petición AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Usuario {$usuario->name} {$usuario->apellido} rechazado"
                ]);
            }
            
            return redirect()->route('usuarios.show', $id)
                           ->with('success', "Usuario {$usuario->name} {$usuario->apellido} rechazado correctamente");
        }
        
        // Si es una petición AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario ya no está pendiente de aprobación'
            ]);
        }
        
        return redirect()->route('usuarios.pendientes')
                       ->with('error', 'El usuario ya no está pendiente de aprobación');
    }

    public function show($id)
    {
        $usuario = Persona::with(['user', 'unidadHabitacional'])->findOrFail($id);
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
        $usuario->name = $request->nombre; // El campo en la BD es 'name' pero el request viene como 'nombre'
        $usuario->apellido = $request->apellido;
        $usuario->CI = $request->CI;
        $usuario->telefono = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->fechaNacimiento = $request->fechaNacimiento;
        $usuario->genero = $request->genero;
        $usuario->estadoCivil = $request->estadoCivil;
        $usuario->nacionalidad = $request->nacionalidad;
        $usuario->ocupacion = $request->ocupacion;
        $usuario->estadoRegistro = $request->estadoRegistro;
        $usuario->save();

        if ($usuario->user) {
            $usuario->user->email = $request->email;
            $usuario->user->save();
        }

        return redirect()->route('usuarios.show', $usuario->id)->with('success', 'Datos actualizados correctamente.');
    }
}
