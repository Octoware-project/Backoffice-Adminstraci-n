<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UserAdmin;
use App\Models\PlanTrabajo;

class PlanTrabajoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar los seeders para tener datos reales
        $this->seed();
    }

    public function test_PuedeListarLosPlanesDeTrabajo()
    {
        // Usar usuario real del seeder
        $usuario = User::where('email', 'user@test.com')->first();
        
        $respuesta = $this->actingAs($usuario)->get(route('plan-trabajos.index'));
        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('admin.horas.planTrabajos');
        $respuesta->assertViewHas('planes');
    }

    public function test_PuedeVerElFormularioDeCreacion()
    {
        // Usar usuario admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $respuesta = $this->actingAs($admin)->get(route('plan-trabajos.create'));
        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('admin.horas.createPlanTrabajo');
        $respuesta->assertViewHas('usuarios');
    }

    public function test_PuedeGuardarUnPlanDeTrabajo()
    {
        // Usar usuarios reales del seeder
        $usuario = User::where('email', 'user@test.com')->first();
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $datos = [
            'user_id' => $usuario->id,
            'mes' => 12, // Diciembre (para no interferir con datos existentes)
            'anio' => 2025,
            'horas_requeridas' => 45,
        ];
        
        $respuesta = $this->actingAs($admin)->post(route('plan-trabajos.store'), $datos);
        $respuesta->assertRedirect(route('plan-trabajos.index'));
        $this->assertDatabaseHas('plan_trabajos', $datos);
    }

    public function test_MuestraErroresDeValidacionAlGuardar()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $datos = [
            'user_id' => null,
            'mes' => 0,
            'anio' => null,
            'horas_requeridas' => 0,
        ];
        
        $respuesta = $this->actingAs($admin)->post(route('plan-trabajos.store'), $datos);
        $respuesta->assertSessionHasErrors(['user_id', 'mes', 'anio', 'horas_requeridas']);
    }

    public function test_PuedeVerUnPlanDeTrabajo()
    {
        // Usar usuario y plan real del seeder
        $usuario = User::where('email', 'user@test.com')->first();
        $plan = PlanTrabajo::where('user_id', $usuario->id)
                          ->where('mes', 9)
                          ->where('anio', 2025)
                          ->first();
        
        $respuesta = $this->actingAs($usuario)->get(route('plan-trabajos.show', $plan->id));
        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('admin.horas.showPlanTrabajo');
        $respuesta->assertViewHas(['plan', 'horas', 'horas_trabajadas', 'porcentaje']);
    }

    public function test_PuedeEliminarUnPlanDeTrabajo()
    {
        // Crear un plan específico para eliminar (no afectar datos del seeder)
        $usuario = User::where('email', 'user@test.com')->first();
        $plan = PlanTrabajo::create([
            'user_id' => $usuario->id,
            'mes' => 11, // Noviembre (mes no usado en seeder)
            'anio' => 2025,
            'horas_requeridas' => 25,
        ]);
        
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $respuesta = $this->actingAs($admin)->delete(route('plan-trabajos.destroy', $plan->id));
        $respuesta->assertRedirect(route('plan-trabajos.index'));
        $this->assertDatabaseMissing('plan_trabajos', ['id' => $plan->id, 'deleted_at' => null]);
    }
}
