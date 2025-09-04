<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JuntasAsambleaController;

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::put('/usuarios/{id}/aceptar', [UsuarioController::class, 'aceptar'])->name('usuarios.aceptar');
Route::put('/usuarios/{id}/rechazar', [UsuarioController::class, 'rechazar'])->name('usuarios.rechazar');
Route::get('/admin/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');
Route::get('/admin/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');


// (Prueba) Residente sube su comprobante de pago
Route::post('/pago', [PagoController::class, 'store']); 

// Backoffice admin
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::post('/pagos/{id}/aprobar', [PagoController::class, 'aprobar'])->name('pagos.aprobar');
    Route::post('/pagos/{id}/rechazar', [PagoController::class, 'rechazar'])->name('pagos.rechazar');
    Route::get('/pagos/persona/{persona_id}', [PagoController::class, 'pagosPorPersona'])->name('pagos.por_persona');

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');

// Página Octoware
Route::get('/octoware', function () {
    return view('octoware');
})->name('octoware');

// Página Administradores
Route::get('/administradores', [AdminController::class, 'index'])->name('admin.list');
Route::post('/administradores', [AdminController::class, 'store'])->name('admin.store');
Route::get('/administradores/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('/administradores/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/administradores/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

// Rutas para el CRUD de juntas
Route::resource('juntas_asamblea', JuntasAsambleaController::class);

Route::get('/asamblea', [JuntasAsambleaController::class, 'vistaAsamblea'])->name('admin.asamblea.index');
