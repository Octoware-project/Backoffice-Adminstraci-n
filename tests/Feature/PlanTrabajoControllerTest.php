<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\PlanTrabajo;

class PlanTrabajoControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function puede_listar_los_planes_de_trabajo()
    {
        $usuario = User::factory()->create();
        PlanTrabajo::factory()->create(['user_id' => $usuario->id]);
        $respuesta = $this->actingAs($usuario)->get(route('plan-trabajos.index'));
        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('admin.horas.planTrabajos');
        $respuesta->assertViewHas('planes');
    }

    /** @test */
    public function puede_ver_el_formulario_de_creacion()
    {
        $usuario = User::factory()->create();
        $respuesta = $this->actingAs($usuario)->get(route('plan-trabajos.create'));
        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('admin.horas.createPlanTrabajo');
        $respuesta->assertViewHas('usuarios');
    }

    /** @test */
    public function puede_guardar_un_plan_de_trabajo()
    {
        $usuario = User::factory()->create();
        $admin = User::factory()->create();
        $datos = [
            'user_id' => $usuario->id,
            'mes' => 5,
            'anio' => 2025,
            'horas_requeridas' => 40,
        ];
        $respuesta = $this->actingAs($admin)->post(route('plan-trabajos.store'), $datos);
        $respuesta->assertRedirect(route('plan-trabajos.index'));
        $this->assertDatabaseHas('plan_trabajos', $datos);
    }

    /** @test */
    public function muestra_errores_de_validacion_al_guardar()
    {
        $admin = User::factory()->create();
        $datos = [
            'user_id' => null,
            'mes' => 0,
            'anio' => null,
            'horas_requeridas' => 0,
        ];
        $respuesta = $this->actingAs($admin)->post(route('plan-trabajos.store'), $datos);
        $respuesta->assertSessionHasErrors(['user_id', 'mes', 'anio', 'horas_requeridas']);
    }

    /** @test */
    public function puede_ver_un_plan_de_trabajo()
    {
        $usuario = User::factory()->create(['email' => 'test@example.com']);
        $plan = PlanTrabajo::factory()->create([
            'user_id' => $usuario->id,
            'mes' => 5,
            'anio' => 2025,
            'horas_requeridas' => 40,
        ]);
        $respuesta = $this->actingAs($usuario)->get(route('plan-trabajos.show', $plan->id));
        $respuesta->assertStatus(200);
        $respuesta->assertViewIs('admin.horas.showPlanTrabajo');
        $respuesta->assertViewHas(['plan', 'horas', 'horas_trabajadas', 'porcentaje']);
    }

    /** @test */
    public function puede_eliminar_un_plan_de_trabajo()
    {
        $usuario = User::factory()->create();
        $plan = PlanTrabajo::factory()->create(['user_id' => $usuario->id]);
        $respuesta = $this->actingAs($usuario)->delete(route('plan-trabajos.destroy', $plan->id));
        $respuesta->assertRedirect(route('plan-trabajos.index'));
        $this->assertDatabaseMissing('plan_trabajos', ['id' => $plan->id]);
    }
}
