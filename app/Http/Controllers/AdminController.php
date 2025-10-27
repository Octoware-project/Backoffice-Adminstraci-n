<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAdmin;

class AdminController extends Controller
{
    public function index()
    {
        try {
            $admins = UserAdmin::all();
            return view('administradores', compact('admins'));
        } catch (\Exception $e) {
            \Log::error('Error al listar administradores: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar los administradores.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:user_admins,email',
                'password' => 'required|string|min:6|confirmed',
            ]);

            UserAdmin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return redirect()->route('admin.list')->with('success', 'Administrador creado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.list')
                ->withInput()
                ->withErrors($e->validator);
        } catch (\Exception $e) {
            \Log::error('Error al crear administrador: ' . $e->getMessage());
            return redirect()->route('admin.list')
                ->withInput()
                ->with('error', 'Error al crear el administrador.');
        }
    }

    public function edit($id)
    {
        try {
            $admin = UserAdmin::findOrFail($id);
            $admins = UserAdmin::all();
            return view('administradores', compact('admins', 'admin'));
        } catch (\Exception $e) {
            \Log::error('Error al editar administrador: ' . $e->getMessage());
            return redirect()->route('admin.list')->with('error', 'Administrador no encontrado.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $admin = UserAdmin::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:user_admins,email,' . $admin->id,
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            $admin->name = $request->name;
            $admin->email = $request->email;
            if ($request->filled('password')) {
                $admin->password = bcrypt($request->password);
            }
            $admin->save();

            return redirect()->route('admin.list')->with('success', 'Administrador actualizado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('admin.list')
                ->withInput()
                ->withErrors($e->validator);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar administrador: ' . $e->getMessage());
            return redirect()->route('admin.list')
                ->withInput()
                ->with('error', 'Error al actualizar el administrador.');
        }
    }

    public function destroy($id)
    {
        try {
            $totalAdmins = UserAdmin::count();
            
            if ($totalAdmins <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el último administrador del sistema. Debe haber al menos un administrador activo.'
                ]);
            }
            
            $admin = UserAdmin::findOrFail($id);
            $admin->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Administrador eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar administrador: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el administrador.'
            ], 500);
        }
    }
}
