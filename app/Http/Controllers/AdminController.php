<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAdmin;

class AdminController extends Controller
{
    public function index()
    {
        $admins = UserAdmin::all();
        return view('administradores', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|min:6',
        ]);

        if ($request->password !== $request->password_confirmation) {
            return redirect()->route('admin.list')
                ->withInput()
                ->with('error', 'Las contraseñas no coinciden.');
        }

        if (UserAdmin::where('email', $request->email)->exists()) {
            return redirect()->route('admin.list')
                ->withInput()
                ->with('error', 'El email ya está registrado.');
        }

        UserAdmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.list')->with('success', 'Administrador creado correctamente.');
    }

    public function edit($id)
    {
        $admin = UserAdmin::findOrFail($id);
        $admins = UserAdmin::all();
        return view('administradores', compact('admins', 'admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = UserAdmin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user_admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
            'password_confirmation' => 'nullable|string|min:6',
        ]);

        if ($request->password && $request->password !== $request->password_confirmation) {
            return redirect()->route('admin.list')
                ->withInput()
                ->with('error', 'Las contraseñas no coinciden.');
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->password) {
            $admin->password = bcrypt($request->password);
        }
        $admin->save();

        return redirect()->route('admin.list')->with('success', 'Administrador actualizado correctamente.');
    }

    public function destroy($id)
    {
        // Verificar si es el último administrador
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
    }
}
