
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



// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('dashboard');


// Página Octoware
Route::get('/octoware', function () {
    return view('octoware');
})->name('octoware');

// Vista de facturas (admin)
use App\Http\Controllers\Admin\FacturaController;
Route::get('/facturas', [FacturaController::class, 'index'])->name('admin.facturas.index');
Route::put('/facturas/{id}/aceptar', [FacturaController::class, 'aceptar'])->name('admin.facturas.aceptar');
Route::put('/facturas/{id}/rechazar', [FacturaController::class, 'rechazar'])->name('admin.facturas.rechazar');
Route::put('/facturas/{id}/cancelar', [FacturaController::class, 'cancelar'])->name('admin.facturas.cancelar');
Route::get('/facturas/usuario/{email}', [FacturaController::class, 'porUsuario'])->where('email', '.*')->name('admin.facturas.usuario');

// Página Administradores
Route::get('/administradores', [AdminController::class, 'index'])->name('admin.list');
Route::post('/administradores', [AdminController::class, 'store'])->name('admin.store');
Route::get('/administradores/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('/administradores/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/administradores/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');

// Rutas para el CRUD de juntas
Route::resource('juntas_asamblea', JuntasAsambleaController::class);

Route::get('/asamblea', [JuntasAsambleaController::class, 'vistaAsamblea'])->name('admin.asamblea.index');

// CRUD Planes de Trabajo
Route::get('/admin/horas/planes-trabajo', [\App\Http\Controllers\PlanTrabajoController::class, 'index'])->name('plan-trabajos.index');
Route::get('/admin/horas/planes-trabajo/create', [\App\Http\Controllers\PlanTrabajoController::class, 'create'])->name('plan-trabajos.create');
Route::post('/admin/horas/planes-trabajo', [\App\Http\Controllers\PlanTrabajoController::class, 'store'])->name('plan-trabajos.store');
Route::get('/admin/horas/planes-trabajo/{id}', [\App\Http\Controllers\PlanTrabajoController::class, 'show'])->name('plan-trabajos.show');
Route::delete('/admin/horas/planes-trabajo/{id}', [\App\Http\Controllers\PlanTrabajoController::class, 'destroy'])->name('plan-trabajos.destroy');
