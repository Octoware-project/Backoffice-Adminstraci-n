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
        return $this->usuariosAceptados($request);
    }

    public function pendientes(Request $request)
    {
        $query = Persona::with('user')->where('estadoRegistro', 'Pendiente');

        if ($request->filled('filter_nombre')) {
            $searchTerm = $request->filter_nombre;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apellido', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(name, ' ', apellido) LIKE ?", ["%{$searchTerm}%"]);
            });
        }

        if ($request->filled('filter_email')) {
            $searchEmail = $request->filter_email;
            $query->whereHas('user', function($q) use ($searchEmail) {
                $q->where('email', 'LIKE', "%{$searchEmail}%");
            });
        }

        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['created_at', 'name', 'email'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        $allowedDirections = ['asc', 'desc'];
        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'desc';
        }

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

        return view('usuarios.pendientes', compact('usuarios'));
    }

    public function usuariosAceptados(Request $request)
    {
        $query = Persona::with('user')->whereIn('estadoRegistro', ['Aceptado', 'Inactivo']);

        if ($request->filled('filter_nombre')) {
            $searchTerm = $request->filter_nombre;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apellido', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(name, ' ', apellido) LIKE ?", ["%{$searchTerm}%"]);
            });
        }

        if ($request->filled('filter_email')) {
            $searchEmail = $request->filter_email;
            $query->whereHas('user', function($q) use ($searchEmail) {
                $q->where('email', 'LIKE', "%{$searchEmail}%");
            });
        }

        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['created_at', 'name', 'email'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        $allowedDirections = ['asc', 'desc'];
        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'desc';
        }

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

        return view('usuarios.index', compact('usuarios'));
    }

    public function aceptar(Request $request, $id)
    {
        try {
            $usuario = Persona::findOrFail($id);
            
            if ($usuario->estadoRegistro === 'Pendiente') {
                $usuario->estadoRegistro = 'Inactivo';
                $usuario->fecha_aceptacion = now();
                $usuario->save();
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Usuario {$usuario->name} {$usuario->apellido} aceptado correctamente (estado: Inactivo)"
                    ]);
                }
                
                return redirect()->route('usuarios.show', $id)
                               ->with('success', "Usuario {$usuario->name} {$usuario->apellido} aceptado correctamente (estado: Inactivo)");
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario ya no está pendiente de aprobación'
                ]);
            }
            
            return redirect()->route('usuarios.pendientes')
                           ->with('error', 'El usuario ya no está pendiente de aprobación');
        } catch (\Exception $e) {
            \Log::error('Error al aceptar usuario: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al aceptar el usuario'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al aceptar el usuario.');
        }
    }

    public function rechazar(Request $request, $id)
    {
        try {
            $usuario = Persona::findOrFail($id);
            
            if ($usuario->estadoRegistro === 'Pendiente') {
                $usuario->estadoRegistro = 'Rechazado';
                $usuario->save();
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => "Usuario {$usuario->name} {$usuario->apellido} rechazado"
                    ]);
                }
                
                return redirect()->route('usuarios.show', $id)
                               ->with('success', "Usuario {$usuario->name} {$usuario->apellido} rechazado correctamente");
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El usuario ya no está pendiente de aprobación'
                ]);
            }
            
            return redirect()->route('usuarios.pendientes')
                           ->with('error', 'El usuario ya no está pendiente de aprobación');
        } catch (\Exception $e) {
            \Log::error('Error al rechazar usuario: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al rechazar el usuario'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al rechazar el usuario.');
        }
    }

    public function show($id)
    {
        try {
            $usuario = Persona::with(['user', 'unidadHabitacional'])->findOrFail($id);
            $password = request()->query('password');
            
            $estadoFacturas = null;
            $totalFacturas = 0;
            
            if ($usuario->user && $usuario->user->email) {
                $facturas = \App\Models\Factura::where('email', $usuario->user->email)->get();
                $totalFacturas = $facturas->count();
                
                if ($totalFacturas > 0) {
                    $estadoFacturas = $this->calcularEstadoFacturas($facturas);
                }
            }
            
            return view('usuarios.show', compact('usuario', 'password', 'estadoFacturas', 'totalFacturas'));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar usuario: ' . $e->getMessage());
            return redirect()->route('usuarios.index')->with('error', 'Usuario no encontrado.');
        }
    }

    private function calcularEstadoFacturas($facturas)
    {
        if ($facturas->isEmpty()) {
            return [
                'estado' => 'Sin facturas',
                'color' => 'secondary',
                'detalle' => 'No hay facturas registradas',
                'icono' => 'fas fa-receipt'
            ];
        }

        $facturasAceptadas = $facturas->where('Estado_Pago', 'Aceptado');
        $facturasPendientes = $facturas->where('Estado_Pago', 'Pendiente');
        $facturasRechazadas = $facturas->where('Estado_Pago', 'Rechazado');
        
        $totalFacturas = $facturas->count();
        $facturasAceptadasCount = $facturasAceptadas->count();
        $facturasPendientesCount = $facturasPendientes->count();
        $facturasRechazadasCount = $facturasRechazadas->count();
        
        $facturasNoPagadas = $facturasPendientesCount + $facturasRechazadasCount;
        
        if ($facturasNoPagadas == 0) {
            return [
                'estado' => 'Al día',
                'color' => 'success',
                'detalle' => "Todas las facturas están aceptadas ({$facturasAceptadasCount}/{$totalFacturas})",
                'icono' => 'fas fa-check-circle'
            ];
        } elseif ($facturasNoPagadas == 1) {
            $detalle = $facturasPendientesCount > 0 ? 
                '1 factura pendiente de aprobación' : 
                '1 factura rechazada';
            return [
                'estado' => 'Pendiente',
                'color' => 'warning',
                'detalle' => $detalle,
                'icono' => 'fas fa-exclamation-triangle'
            ];
        } else {
            $pendientesText = $facturasPendientesCount > 0 ? "{$facturasPendientesCount} pendientes" : '';
            $rechazadasText = $facturasRechazadasCount > 0 ? "{$facturasRechazadasCount} rechazadas" : '';
            
            $detalle = trim($pendientesText . ($pendientesText && $rechazadasText ? ', ' : '') . $rechazadasText);
            
            return [
                'estado' => "{$facturasNoPagadas} facturas sin aprobar",
                'color' => 'danger',
                'detalle' => $detalle,
                'icono' => 'fas fa-times-circle'
            ];
        }
    }

    public function edit($id)
    {
        $usuario = Persona::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        try {
            $usuario = Persona::findOrFail($id);

            $usuario->name = $request->nombre;
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
                $usuario->user->name = $request->nombre;
                $usuario->user->save();
            }

            return redirect()->route('usuarios.show', $usuario->id)->with('success', 'Datos actualizados correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al actualizar los datos del usuario.');
        }
    }

    public function destroy($id)
    {
        try {
            $usuario = Persona::with('unidadHabitacional')->findOrFail($id);
            $nombreCompleto = "{$usuario->name} {$usuario->apellido}";
            
            if ($usuario->unidad_habitacional_id) {
                $unidadInfo = $usuario->unidadHabitacional ? 
                    "Unidad {$usuario->unidadHabitacional->numero}" : 
                    "una unidad habitacional";
                    
                return redirect()->route('usuarios.index')
                               ->with('error', "No se puede eliminar al usuario {$nombreCompleto} porque tiene asignada la {$unidadInfo}. Primero debe desasignarse la unidad desde la sección de Unidades Habitacionales.");
            }
            
            $usuario->delete();
            
            return redirect()->route('usuarios.index')
                           ->with('success', "Usuario {$nombreCompleto} eliminado correctamente. Se puede restaurar desde usuarios eliminados.");
        } catch (\Exception $e) {
            \Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar el usuario.');
        }
    }

    public function eliminados(Request $request)
    {
        $query = Persona::onlyTrashed()->with('user');

        if ($request->filled('filter_nombre')) {
            $searchTerm = $request->filter_nombre;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apellido', 'LIKE', "%{$searchTerm}%")
                  ->orWhereRaw("CONCAT(name, ' ', apellido) LIKE ?", ["%{$searchTerm}%"]);
            });
        }

        if ($request->filled('filter_email')) {
            $searchEmail = $request->filter_email;
            $query->whereHas('user', function($q) use ($searchEmail) {
                $q->where('email', 'LIKE', "%{$searchEmail}%");
            });
        }

        $sortField = $request->get('sort_field', 'deleted_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['deleted_at', 'name', 'email'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'deleted_at';
        }

        $allowedDirections = ['asc', 'desc'];
        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'desc';
        }

        switch ($sortField) {
            case 'name':
                $query->orderByRaw("CONCAT(name, ' ', apellido) {$sortDirection}");
                break;
            case 'email':
                $query->leftJoin('users', 'personas.user_id', '=', 'users.id')
                      ->orderBy('users.email', $sortDirection)
                      ->select('personas.*');
                break;
            case 'deleted_at':
            default:
                $query->orderBy('deleted_at', $sortDirection);
                break;
        }

        $usuarios = $query->get();

        return view('usuarios.eliminados', compact('usuarios'));
    }

    public function restaurar($id)
    {
        try {
            $usuario = Persona::onlyTrashed()->findOrFail($id);
            $nombreCompleto = "{$usuario->name} {$usuario->apellido}";
            
            $usuario->restore();
            
            return redirect()->route('usuarios.eliminados')
                           ->with('success', "Usuario {$nombreCompleto} restaurado correctamente.");
        } catch (\Exception $e) {
            \Log::error('Error al restaurar usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al restaurar el usuario.');
        }
    }
}
