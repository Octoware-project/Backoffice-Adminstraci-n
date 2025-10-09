<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\PlanTrabajo;
use App\Models\User;
use App\Models\UserAdmin;
use App\Models\Persona;
use App\Models\Horas_Mensuales;
use Carbon\Carbon;

class PlanTrabajoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_IndexMuestraPlanesDeTrabajoConMetricas()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.horas.planTrabajos');
        $response->assertViewHas('planes');
        
        $planes = $response->viewData('planes');
        $this->assertGreaterThan(0, $planes->count());
        
        $primerPlan = $planes->first();
        if ($primerPlan) {
            $this->assertTrue(isset($primerPlan->horas_trabajadas));
            $this->assertTrue(isset($primerPlan->porcentaje));
            $this->assertTrue(isset($primerPlan->is_completed));
        }
    }

    public function test_IndexFiltraPorUsuario()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $usuario = User::where('email', 'aceptado1@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.index', [
            'filter_user' => $usuario->email
        ]));
        
        $response->assertStatus(200);
        $planes = $response->viewData('planes');
        
        foreach ($planes as $plan) {
            $this->assertEquals($usuario->email, $plan->user->email);
        }
    }

    public function test_IndexFiltraPorMes()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.index', [
            'filter_month' => 11
        ]));
        
        $response->assertStatus(200);
        $planes = $response->viewData('planes');
        
        foreach ($planes as $plan) {
            $this->assertEquals(11, $plan->mes);
        }
    }

    public function test_CreateMuestraFormularioCreacion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.horas.createPlanTrabajo');
        $response->assertViewHas('usuarios');
    }

    public function test_StoreCreaPlaneTrabajoExitosamente()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $usuario = User::whereHas('persona', function ($query) {
            $query->where('estadoRegistro', 'Aceptado');
        })->first();
        
        $datosPlane = [
            'user_id' => $usuario->id,
            'mes' => 1,
            'anio' => 2025,
            'horas_requeridas' => 40
        ];
        
        $response = $this->actingAs($admin)->post(route('plan-trabajos.store'), $datosPlane);
        
        $response->assertRedirect(route('plan-trabajos.index'));
        $response->assertSessionHas('success', 'Plan de trabajo creado');
        
        $this->assertDatabaseHas('plan_trabajos', [
            'user_id' => $usuario->id,
            'mes' => 1,
            'anio' => 2025,
            'horas_requeridas' => 40
        ]);
    }

    public function test_StoreValidaDatosRequeridos()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->post(route('plan-trabajos.store'), []);
        
        $response->assertSessionHasErrors(['user_id', 'mes', 'anio', 'horas_requeridas']);
    }

    public function test_StoreNoPermiteDuplicados()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $planExistente = PlanTrabajo::first();
        
        $datosPlane = [
            'user_id' => $planExistente->user_id,
            'mes' => $planExistente->mes,
            'anio' => $planExistente->anio,
            'horas_requeridas' => 35
        ];
        
        $response = $this->actingAs($admin)->post(route('plan-trabajos.store'), $datosPlane);
        
        $response->assertSessionHasErrors(['duplicate']);
    }

    public function test_ShowMuestraPlanConDetalles()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $plan = PlanTrabajo::with('user')->first();
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.show', $plan->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.horas.showPlanTrabajo');
        $response->assertViewHas(['plan', 'horas', 'horas_trabajadas', 'horas_reales', 'horas_justificadas', 'porcentaje']);
        
        $this->assertEquals($plan->id, $response->viewData('plan')->id);
    }

    public function test_DestroyEliminaPlanTrabajo()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $plan = PlanTrabajo::first();
        
        $response = $this->actingAs($admin)->delete(route('plan-trabajos.destroy', $plan->id));
        
        $response->assertRedirect(route('plan-trabajos.index'));
        $response->assertSessionHas('success', 'Plan de trabajo eliminado exitosamente');
        
        $this->assertSoftDeleted('plan_trabajos', ['id' => $plan->id]);
    }

    public function test_CalculaMetricasCorrectamente()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $plan = PlanTrabajo::with('user')->first();
        
        Horas_Mensuales::create([
            'email' => $plan->user->email,
            'mes' => $plan->mes,
            'anio' => $plan->anio,
            'dia' => 15,
            'Cantidad_Horas' => 20
        ]);
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.show', $plan->id));
        
        $response->assertStatus(200);
        $horas_trabajadas = $response->viewData('horas_trabajadas');
        $porcentaje = $response->viewData('porcentaje');
        
        $this->assertEquals(20, $horas_trabajadas);
        $this->assertEquals(round((20 / $plan->horas_requeridas) * 100, 2), $porcentaje);
    }

    public function test_FiltroCompletadosFunciona()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.index', [
            'filter_completed' => 'completed'
        ]));
        
        $response->assertStatus(200);
        $planes = $response->viewData('planes');
        
        foreach ($planes as $plan) {
            $this->assertTrue($plan->is_completed);
        }
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.index', [
            'filter_completed' => 'incomplete'
        ]));
        
        $response->assertStatus(200);
        $planes = $response->viewData('planes');
        
        foreach ($planes as $plan) {
            $this->assertFalse($plan->is_completed);
        }
    }

    public function test_OrdenamientoFunciona()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('plan-trabajos.index', [
            'sort_progress' => 'asc'
        ]));
        
        $response->assertStatus(200);
        $planes = $response->viewData('planes');
        
        $porcentajes = $planes->pluck('porcentaje')->toArray();
        $porcentajesOrdenados = $porcentajes;
        sort($porcentajesOrdenados);
        
        $this->assertEquals($porcentajesOrdenados, $porcentajes);
    }

    public function test_PlanesNoviembre2024Existen()
    {
        $planesNoviembre = PlanTrabajo::where('mes', 11)
                                   ->where('anio', 2024)
                                   ->whereNull('deleted_at')
                                   ->get();
        
        $this->assertGreaterThanOrEqual(10, $planesNoviembre->count());
        
        foreach ($planesNoviembre as $plan) {
            $this->assertNotNull($plan->user);
            $this->assertEquals('Aceptado', $plan->user->persona->estadoRegistro);
        }
    }

    public function test_PlanesAsociadosAUsuariosReales()
    {
        $planes = PlanTrabajo::with('user.persona')->get();
        
        foreach ($planes as $plan) {
            $this->assertNotNull($plan->user);
            $this->assertNotNull($plan->user->persona);
            $this->assertContains($plan->user->persona->estadoRegistro, ['Aceptado', 'Inactivo', 'Pendiente']);
        }
    }
}