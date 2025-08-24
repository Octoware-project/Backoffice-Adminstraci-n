<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AuthController;

Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
Route::put('/usuarios/{id}/aceptar', [UsuarioController::class, 'aceptar'])->name('usuarios.aceptar');
Route::put('/usuarios/{id}/rechazar', [UsuarioController::class, 'rechazar'])->name('usuarios.rechazar');


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
