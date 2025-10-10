<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UserAdmin;
use App\Models\User;
use App\Models\Persona;
use App\Models\Factura;
use App\Models\UnidadHabitacional;
use App\Models\PlanTrabajo;
use App\Models\JuntasAsamblea;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = UserAdmin::where('email', 'admin@example.com')->first();
    }

    public function test_dashboard_muestra_vista_correcta()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        
        $response->assertStatus(200)
                 ->assertViewIs('dashboard')
                 ->assertViewHas('metrics');
    }

    public function test_dashboard_requiere_autenticacion()
    {
        $response = $this->get(route('dashboard'));
        
        $response->assertRedirect(route('login'));
    }

    public function test_metrics_api_retorna_json_con_metricas()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard.metrics'));
        
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'total_usuarios',
                     'usuarios_pendientes',
                     'usuarios_nuevos_mes',
                     'usuarios_aceptados_mes',
                     'ingresos_mes',
                     'facturas_pendientes',
                     'facturas_vencidas',
                     'porcentaje_cobro',
                     'total_unidades',
                     'unidades_ocupadas',
                     'porcentaje_ocupacion',
                     'planes_activos',
                     'horas_completadas',
                     'ingresos_mensuales',
                     'usuarios_mensuales',
                     'asambleas_proximas'
                 ]);
    }

    public function test_alerts_api_retorna_json_con_alertas()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard.alerts'));
        
        $response->assertStatus(200)
                 ->assertJson([]);
    }

    public function test_metricas_calculan_usuarios_correctamente()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard.metrics'));
        $metrics = $response->json();
        
        $this->assertIsInt($metrics['total_usuarios']);
        $this->assertIsInt($metrics['usuarios_pendientes']);
        $this->assertGreaterThanOrEqual(0, $metrics['total_usuarios']);
        $this->assertGreaterThanOrEqual(0, $metrics['usuarios_pendientes']);
    }

    public function test_metricas_calculan_facturas_correctamente()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard.metrics'));
        $metrics = $response->json();
        
        $this->assertIsInt($metrics['facturas_pendientes']);
        $this->assertIsNumeric($metrics['ingresos_mes']);
        $this->assertIsInt($metrics['porcentaje_cobro']);
        $this->assertGreaterThanOrEqual(0, $metrics['porcentaje_cobro']);
        $this->assertLessThanOrEqual(100, $metrics['porcentaje_cobro']);
    }

    public function test_metricas_contienen_todas_las_claves_requeridas()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard.metrics'));
        $metrics = $response->json();
        
        // Verificar que todas las métricas están presentes
        $requiredKeys = [
            'total_usuarios', 'usuarios_pendientes', 'usuarios_nuevos_mes',
            'ingresos_mes', 'facturas_pendientes', 'porcentaje_cobro'
        ];
        
        foreach ($requiredKeys as $key) {
            $this->assertArrayHasKey($key, $metrics, "La métrica '$key' no está presente");
        }
    }

    public function test_dashboard_carga_correctamente_con_datos_seeder()
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        
        $response->assertStatus(200)
                 ->assertViewIs('dashboard')
                 ->assertViewHas('metrics');
        
        $metrics = $response->viewData('metrics');
        $this->assertIsArray($metrics);
        $this->assertNotEmpty($metrics);
    }
}