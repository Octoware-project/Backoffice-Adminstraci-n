
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JuntasAsambleaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ConfiguracionHorasController;
use App\Http\Controllers\UnidadHabitacionalController;


Route::middleware('auth')->group(function () {
// Rutas para usuarios
Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index'); // Usuarios aceptados
Route::get('/usuarios/pendientes', [UsuarioController::class, 'pendientes'])->name('usuarios.pendientes'); // Usuarios pendientes
Route::get('/usuarios/eliminados', [UsuarioController::class, 'eliminados'])->name('usuarios.eliminados'); // Usuarios eliminados
Route::put('/usuarios/{id}/aceptar', [UsuarioController::class, 'aceptar'])->name('usuarios.aceptar');
Route::put('/usuarios/{id}/rechazar', [UsuarioController::class, 'rechazar'])->name('usuarios.rechazar');
Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
Route::put('/usuarios/{id}/restaurar', [UsuarioController::class, 'restaurar'])->name('usuarios.restaurar');
Route::post('/admin/usuarios/{id}/aceptar', [UsuarioController::class, 'aceptar'])->name('usuarios.aceptar.ajax');
Route::post('/admin/usuarios/{id}/rechazar', [UsuarioController::class, 'rechazar'])->name('usuarios.rechazar.ajax');
Route::get('/admin/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');
Route::get('/admin/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
Route::put('/admin/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');

// Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/dashboard/metrics', [DashboardController::class, 'getMetricsApi'])->middleware('auth')->name('dashboard.metrics');
Route::get('/dashboard/alerts', [DashboardController::class, 'getAlerts'])->middleware('auth')->name('dashboard.alerts');


// Página Octoware
Route::get('/octoware', function () {
    return view('octoware');
})->name('octoware');

// Vista de facturas (admin)
Route::get('/facturas', [FacturaController::class, 'index'])->name('admin.facturas.index');
Route::get('/facturas/archivadas', [FacturaController::class, 'archivadas'])->name('admin.facturas.archivadas');
Route::put('/facturas/{id}/aceptar', [FacturaController::class, 'aceptar'])->name('admin.facturas.aceptar');
Route::put('/facturas/{id}/rechazar', [FacturaController::class, 'rechazar'])->name('admin.facturas.rechazar');
Route::put('/facturas/{id}/cancelar', [FacturaController::class, 'cancelar'])->name('admin.facturas.cancelar');
Route::delete('/facturas/{id}/eliminar', [FacturaController::class, 'eliminar'])->name('admin.facturas.eliminar');
Route::get('/facturas/usuario/{email}', [FacturaController::class, 'porUsuario'])->where('email', '.*')->name('admin.facturas.usuario');

// Página Administradores
Route::get('/administradores', [AdminController::class, 'index'])->name('admin.list');
Route::post('/administradores', [AdminController::class, 'store'])->name('admin.store');
Route::get('/administradores/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
Route::put('/administradores/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/administradores/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');



// Rutas para el CRUD de juntas de asamblea (coherentes con el resto)
Route::get('/admin/juntas_asamblea', [JuntasAsambleaController::class, 'index'])->name('admin.juntas_asamblea.index');
Route::get('/admin/juntas_asamblea/create', [JuntasAsambleaController::class, 'create'])->name('admin.juntas_asamblea.create');
Route::post('/admin/juntas_asamblea', [JuntasAsambleaController::class, 'store'])->name('admin.juntas_asamblea.store');
Route::get('/admin/juntas_asamblea/{id}', [JuntasAsambleaController::class, 'show'])->name('admin.juntas_asamblea.show');
Route::get('/admin/juntas_asamblea/{id}/edit', [JuntasAsambleaController::class, 'edit'])->name('admin.juntas_asamblea.edit');
Route::put('/admin/juntas_asamblea/{id}', [JuntasAsambleaController::class, 'update'])->name('admin.juntas_asamblea.update');
Route::delete('/admin/juntas_asamblea/{id}', [JuntasAsambleaController::class, 'destroy'])->name('admin.juntas_asamblea.destroy');

// Alias para vista de asamblea (index)
Route::get('/asamblea', [JuntasAsambleaController::class, 'vistaAsamblea'])->name('admin.asamblea.index');

// CRUD Planes de Trabajo
Route::get('/admin/horas/planes-trabajo', [\App\Http\Controllers\PlanTrabajoController::class, 'index'])->name('plan-trabajos.index');
Route::get('/admin/horas/planes-trabajo/create', [\App\Http\Controllers\PlanTrabajoController::class, 'create'])->name('plan-trabajos.create');
Route::post('/admin/horas/planes-trabajo', [\App\Http\Controllers\PlanTrabajoController::class, 'store'])->name('plan-trabajos.store');
Route::get('/admin/horas/planes-trabajo/{id}', [\App\Http\Controllers\PlanTrabajoController::class, 'show'])->name('plan-trabajos.show');
Route::delete('/admin/horas/planes-trabajo/{id}', [\App\Http\Controllers\PlanTrabajoController::class, 'destroy'])->name('plan-trabajos.destroy');

// Rutas de configuración de horas
Route::get('/admin/horas/configuracion', [ConfiguracionHorasController::class, 'index'])->name('configuracion-horas.index');
Route::put('/admin/horas/configuracion', [ConfiguracionHorasController::class, 'update'])->name('configuracion-horas.update');
Route::post('/admin/horas/configuracion/recalcular', [ConfiguracionHorasController::class, 'recalcularRegistros'])->name('configuracion-horas.recalcular');
Route::get('/admin/horas/configuracion/historial', [ConfiguracionHorasController::class, 'historial'])->name('configuracion-horas.historial');

// CRUD Unidades Habitacionales
Route::get('/unidades/personas-disponibles', [UnidadHabitacionalController::class, 'personasDisponibles'])->name('unidades.personas-disponibles');
Route::resource('unidades', UnidadHabitacionalController::class);
Route::post('/unidades/{unidad}/asignar-persona', [UnidadHabitacionalController::class, 'asignarPersona'])->name('unidades.asignar-persona');
Route::delete('/unidades/{unidad}/desasignar-persona/{persona}', [UnidadHabitacionalController::class, 'desasignarPersona'])->name('unidades.desasignar-persona');

}); // Cierre del grupo de middleware 'auth'


// Ruta raíz: redirige según autenticación
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

// Rutas de autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');