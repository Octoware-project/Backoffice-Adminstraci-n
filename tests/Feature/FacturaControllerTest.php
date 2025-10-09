<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Factura;
use App\Models\UserAdmin;
use App\Models\User;
use App\Models\Persona;

class FacturaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar los seeders para tener datos reales
        $this->seed();
    }

    public function test_IndexMuestraFacturasPendientes()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.facturas.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.facturas.index');
        $response->assertViewHas(['facturas', 'totalFacturasPendientes']);
        
        // Verificar que solo muestra facturas pendientes
        $facturas = $response->viewData('facturas');
        foreach ($facturas as $factura) {
            $this->assertEquals('Pendiente', $factura->Estado_Pago);
        }
    }

    public function test_ArchivadasMuestraFacturasAceptadasYRechazadas()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.facturas.archivadas'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.facturas.archivadas');
        $response->assertViewHas('facturas');
        
        // Verificar que solo muestra facturas archivadas (Aceptado o Rechazado)
        $facturas = $response->viewData('facturas');
        foreach ($facturas as $factura) {
            $this->assertContains($factura->Estado_Pago, ['Aceptado', 'Rechazado']);
        }
    }

    public function test_PorUsuarioMuestraFacturasDelUsuario()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Obtener un usuario que tenga facturas
        $usuario = User::whereHas('persona', function($query) {
            $query->where('estadoRegistro', 'Aceptado');
        })->first();
        
        $response = $this->actingAs($admin)->get(route('admin.facturas.usuario', $usuario->email));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.facturas.usuario');
        $response->assertViewHas(['facturas', 'usuario', 'estadoPagos']);
        
        // Verificar que todas las facturas pertenecen al usuario
        $facturas = $response->viewData('facturas');
        foreach ($facturas as $factura) {
            $this->assertEquals($usuario->email, $factura->email);
        }
    }

    public function test_AceptarFacturaCambiaEstado()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Buscar una factura pendiente
        $factura = Factura::where('Estado_Pago', 'Pendiente')->first();
        
        $response = $this->actingAs($admin)->put(route('admin.facturas.aceptar', $factura->id));
        
        $response->assertRedirect(route('admin.facturas.index'));
        $response->assertSessionHas('success');
        
        // Verificar que el estado cambió
        $this->assertEquals('Aceptado', $factura->fresh()->Estado_Pago);
    }

    public function test_RechazarFacturaCambiaEstado()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Buscar una factura pendiente
        $factura = Factura::where('Estado_Pago', 'Pendiente')->first();
        
        $response = $this->actingAs($admin)->put(route('admin.facturas.rechazar', $factura->id));
        
        $response->assertRedirect(route('admin.facturas.index'));
        $response->assertSessionHas('success');
        
        // Verificar que el estado cambió
        $this->assertEquals('Rechazado', $factura->fresh()->Estado_Pago);
    }

    public function test_CancelarFacturaRegresaAPendiente()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Buscar una factura aceptada y cambiarla para el test
        $factura = Factura::where('Estado_Pago', 'Aceptado')->first();
        
        $response = $this->actingAs($admin)->put(route('admin.facturas.cancelar', $factura->id));
        
        $response->assertRedirect(route('admin.facturas.index'));
        $response->assertSessionHas('success');
        
        // Verificar que el estado cambió a Pendiente
        $this->assertEquals('Pendiente', $factura->fresh()->Estado_Pago);
    }

    public function test_EliminarFacturaUsaSoftDelete()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Buscar una factura para eliminar
        $factura = Factura::first();
        $facturaId = $factura->id;
        
        $response = $this->actingAs($admin)->delete(route('admin.facturas.eliminar', $facturaId));
        
        $response->assertRedirect(route('admin.facturas.index'));
        $response->assertSessionHas('success');
        
        // Verificar que se usó soft delete
        $this->assertSoftDeleted('Factura', ['id' => $facturaId]);
    }

    public function test_FiltrosPorAñoYMesFuncionanEnIndex()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Probar filtro por año
        $response = $this->actingAs($admin)->get(route('admin.facturas.index', ['año' => 2025]));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.facturas.index');
        
        // Probar filtro por mes
        $response = $this->actingAs($admin)->get(route('admin.facturas.index', ['mes' => 10]));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.facturas.index');
    }

    public function test_FiltrosPorEstadoFuncionanEnArchivadas()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Probar filtro por estado Aceptado
        $response = $this->actingAs($admin)->get(route('admin.facturas.archivadas', ['estado' => 'Aceptado']));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.facturas.archivadas');
        
        // Verificar que solo muestra facturas aceptadas
        $facturas = $response->viewData('facturas');
        foreach ($facturas as $factura) {
            $this->assertEquals('Aceptado', $factura->Estado_Pago);
        }
    }

    public function test_AccionesDesdeVistaUsuarioRedirigencorrectamente()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Buscar una factura pendiente
        $factura = Factura::where('Estado_Pago', 'Pendiente')->first();
        
        // Test aceptar desde vista de usuario
        $response = $this->actingAs($admin)->put(route('admin.facturas.aceptar', $factura->id), [
            'from_user' => '1'
        ]);
        
        $response->assertRedirect(route('admin.facturas.usuario', $factura->email));
        $response->assertSessionHas('success');
    }

    public function test_MostrarFacturasRechazadasDeUsuario()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Obtener un usuario que tenga facturas
        $usuario = User::whereHas('persona', function($query) {
            $query->where('estadoRegistro', 'Aceptado');
        })->first();
        
        // Crear una factura rechazada para el test
        Factura::create([
            'email' => $usuario->email,
            'Monto' => 1000,
            'Estado_Pago' => 'Rechazado',
            'Archivo_Comprobante' => 'comprobantes/comprobante1.pdf',
            'tipo_pago' => 'Transferencia',
            'fecha_pago' => '2025-10-01'
        ]);
        
        // Probar vista de facturas rechazadas
        $response = $this->actingAs($admin)->get(route('admin.facturas.usuario', [
            'email' => $usuario->email,
            'rechazadas' => '1'
        ]));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.facturas.usuario');
        
        // Verificar que solo muestra facturas rechazadas
        $facturas = $response->viewData('facturas');
        foreach ($facturas as $factura) {
            $this->assertEquals('Rechazado', $factura->Estado_Pago);
        }
    }

    public function test_RequiereAutenticacionParaTodasLasRutas()
    {
        // Probar que todas las rutas requieren autenticación
        $factura = Factura::first();
        $usuario = User::first();
        
        $response = $this->get(route('admin.facturas.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('admin.facturas.archivadas'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('admin.facturas.usuario', $usuario->email));
        $response->assertRedirect(route('login'));
        
        $response = $this->put(route('admin.facturas.aceptar', $factura->id));
        $response->assertRedirect(route('login'));
        
        $response = $this->put(route('admin.facturas.rechazar', $factura->id));
        $response->assertRedirect(route('login'));
        
        $response = $this->put(route('admin.facturas.cancelar', $factura->id));
        $response->assertRedirect(route('login'));
        
        $response = $this->delete(route('admin.facturas.eliminar', $factura->id));
        $response->assertRedirect(route('login'));
    }

    public function test_CalculoCorrectodeEstadoPagos()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Obtener un usuario con facturas
        $usuario = User::whereHas('persona', function($query) {
            $query->where('estadoRegistro', 'Aceptado');
        })->first();
        
        $response = $this->actingAs($admin)->get(route('admin.facturas.usuario', $usuario->email));
        
        $response->assertStatus(200);
        $estadoPagos = $response->viewData('estadoPagos');
        
        // Verificar que el estado de pagos tiene las claves esperadas
        $this->assertArrayHasKey('estado', $estadoPagos);
        $this->assertArrayHasKey('color', $estadoPagos);
        $this->assertArrayHasKey('detalle', $estadoPagos);
        
        // Verificar que el estado es uno de los valores esperados o sigue el patrón correcto
        $estadosValidos = ['Al día', 'Pendiente', 'Sin facturas'];
        $esEstadoValido = in_array($estadoPagos['estado'], $estadosValidos) || 
                         preg_match('/^\d+ facturas sin aprobar$/', $estadoPagos['estado']);
        
        $this->assertTrue($esEstadoValido, 
            "El estado '{$estadoPagos['estado']}' no es válido");
        
        $this->assertContains($estadoPagos['color'], ['success', 'warning', 'danger', 'secondary']);
    }
}