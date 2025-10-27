<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        try {
            // Si el usuario ya está autenticado, redirigir al dashboard
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }
            
            return view('login');
        } catch (\Exception $e) {
            \Log::error('Error al mostrar formulario de login: ' . $e->getMessage());
            return view('login');
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('dashboard');
            }
            
            return back()->withErrors([
                'email' => 'Credenciales incorrectas.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en login: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Error al procesar el inicio de sesión.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login');
        } catch (\Exception $e) {
            \Log::error('Error en logout: ' . $e->getMessage());
            return redirect('/login');
        }
    }

    public function dashboard()
    {
        try {
            return view('dashboard');
        } catch (\Exception $e) {
            \Log::error('Error al cargar dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar el dashboard.');
        }
    }
}
