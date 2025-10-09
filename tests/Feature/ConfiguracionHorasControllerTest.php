<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ConfiguracionHoras;
use App\Models\UserAdmin;
use App\Models\Horas_Mensuales;
use App\Models\User;
use Carbon\Carbon;

class ConfiguracionHorasControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_IndexMuestraConfiguracionActualYHistorial()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('configuracion-horas.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.horas.configuracion');
        $response->assertViewHas(['configuracion', 'historial', 'totalRegistrosConJustificacion', 'totalRegistrosCalculados']);
        
        $configuracion = $response->viewData('configuracion');
        $this->assertNotNull($configuracion);
        $this->assertTrue($configuracion->activo);
    }

    public function test_IndexMuestraEstadisticas()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $usuario = User::first();
        if ($usuario) {
            Horas_Mensuales::create([
                'email' => $usuario->email,
                'anio' => 2024,
                'mes' => 10,
                'dia' => 15,
                'Cantidad_Horas' => 8,
                'Monto_Compensario' => 1000.00,
                'valor_hora_al_momento' => 350.00
            ]);
        }
        
        $response = $this->actingAs($admin)->get(route('configuracion-horas.index'));
        
        $response->assertStatus(200);
        $totalRegistrosConJustificacion = $response->viewData('totalRegistrosConJustificacion');
        $this->assertGreaterThanOrEqual(1, $totalRegistrosConJustificacion);
    }

    public function test_UpdateActualizaValorPorHora()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $nuevoValor = 375.50;
        $observaciones = 'Nuevo valor establecido por test';
        
        $response = $this->actingAs($admin)->put(route('configuracion-horas.update'), [
            'valor_por_hora' => $nuevoValor,
            'observaciones' => $observaciones
        ]);
        
        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('configuracion_horas', [
            'valor_por_hora' => $nuevoValor,
            'activo' => true,
            'observaciones' => $observaciones
        ]);
        
        $configuracionesInactivas = ConfiguracionHoras::where('activo', false)->count();
        $this->assertGreaterThan(0, $configuracionesInactivas);
    }

    public function test_UpdateValidaDatosRequeridos()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->put(route('configuracion-horas.update'), []);
        
        $response->assertSessionHasErrors(['valor_por_hora']);
    }

    public function test_UpdateValidaValorMinimo()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->put(route('configuracion-horas.update'), [
            'valor_por_hora' => -10
        ]);
        
        $response->assertSessionHasErrors(['valor_por_hora']);
    }

    public function test_RecalcularRegistrosActualizaHorasExistentes()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $usuario = User::first();
        
        if ($usuario) {
            $registro = Horas_Mensuales::create([
                'email' => $usuario->email,
                'anio' => 2024,
                'mes' => 10,
                'dia' => 20,
                'Cantidad_Horas' => 6,
                'Monto_Compensario' => 700.00,
            ]);
            
            $response = $this->actingAs($admin)->post(route('configuracion-horas.recalcular'));
            
            $response->assertRedirect();
            $response->assertSessionHas('success');
            
            $registroActualizado = Horas_Mensuales::find($registro->id);
            $this->assertNotNull($registroActualizado->valor_hora_al_momento);
            $this->assertNotNull($registroActualizado->horas_equivalentes_calculadas);
        }
    }

    public function test_RecalcularRegistrosSinValorConfiguracion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        ConfiguracionHoras::where('activo', true)->update(['activo' => false]);
        
        $response = $this->actingAs($admin)->post(route('configuracion-horas.recalcular'));
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_HistorialMuestraTodasLasConfiguraciones()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('configuracion-horas.historial'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.horas.historialConfiguracion');
        $response->assertViewHas('configuraciones');
        
        $configuraciones = $response->viewData('configuraciones');
        $this->assertGreaterThan(0, $configuraciones->count());
    }

    public function test_SoloUnaConfiguracionActivaALaVez()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $this->actingAs($admin)->put(route('configuracion-horas.update'), [
            'valor_por_hora' => 400.00,
            'observaciones' => 'Test múltiples configuraciones'
        ]);
        
        $configuracionesActivas = ConfiguracionHoras::where('activo', true)->count();
        $this->assertEquals(1, $configuracionesActivas);
    }

    public function test_GetValorActualFunciona()
    {
        $valorActual = ConfiguracionHoras::getValorActual();
        $this->assertGreaterThan(0, $valorActual);
        
        $configuracionActiva = ConfiguracionHoras::where('activo', true)->first();
        $this->assertEquals($configuracionActiva->valor_por_hora, $valorActual);
    }

    public function test_GetConfiguracionActualFunciona()
    {
        $configuracionActual = ConfiguracionHoras::getConfiguracionActual();
        $this->assertNotNull($configuracionActual);
        $this->assertTrue($configuracionActual->activo);
    }

    public function test_GetHistorialFunciona()
    {
        $historial = ConfiguracionHoras::getHistorial(5);
        $this->assertLessThanOrEqual(5, $historial->count());
        
        $fechas = $historial->pluck('created_at');
        for ($i = 0; $i < $fechas->count() - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                $fechas[$i + 1]->timestamp,
                $fechas[$i]->timestamp
            );
        }
    }

    public function test_ActivarConfiguracionDesactivaOtras()
    {
        $nuevaConfig = ConfiguracionHoras::create([
            'valor_por_hora' => 380.00,
            'activo' => false,
            'observaciones' => 'Configuración para test de activación'
        ]);
        
        $nuevaConfig->activar();
        
        $configuracionesActivas = ConfiguracionHoras::where('activo', true)->count();
        $this->assertEquals(1, $configuracionesActivas);
        
        $this->assertTrue($nuevaConfig->fresh()->activo);
    }

    public function test_ConfiguracionesSeederCreaHistorialCompleto()
    {
        $totalConfiguraciones = ConfiguracionHoras::count();
        $this->assertGreaterThan(5, $totalConfiguraciones);
        
        $configuracionesActivas = ConfiguracionHoras::where('activo', true)->count();
        $this->assertEquals(1, $configuracionesActivas);
        
        $configuracionesInactivas = ConfiguracionHoras::where('activo', false)->count();
        $this->assertGreaterThan(0, $configuracionesInactivas);
    }

    public function test_SeederCreaConfiguracionesEnOrdenCronologico()
    {
        $configuraciones = ConfiguracionHoras::orderBy('created_at')->get();
        
        for ($i = 0; $i < $configuraciones->count() - 1; $i++) {
            $this->assertLessThanOrEqual(
                $configuraciones[$i + 1]->created_at,
                $configuraciones[$i]->created_at
            );
        }
        
        $configuracionActiva = ConfiguracionHoras::where('activo', true)->first();
        $this->assertNotNull($configuracionActiva);
        
        $this->assertGreaterThan(0, $configuracionActiva->valor_por_hora);
    }
}