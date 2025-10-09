<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UnidadHabitacional;
use App\Models\UserAdmin;
use App\Models\Persona;
use App\Models\User;

class UnidadHabitacionalControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_IndexMuestraUnidadesHabitacionales()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('unidades.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.unidades.index');
        $response->assertViewHas('unidades');
        
        $unidades = $response->viewData('unidades');
        $this->assertGreaterThan(0, $unidades->count());
    }

    public function test_IndexFiltraPorNumeroDepartamento()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $unidad = UnidadHabitacional::first();
        
        $response = $this->actingAs($admin)->get(route('unidades.index', [
            'numero_departamento' => $unidad->numero_departamento
        ]));
        
        $response->assertStatus(200);
        $unidades = $response->viewData('unidades');
        
        foreach ($unidades as $u) {
            $this->assertStringContainsString($unidad->numero_departamento, $u->numero_departamento);
        }
    }

    public function test_IndexFiltraPorPiso()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('unidades.index', [
            'piso' => 1
        ]));
        
        $response->assertStatus(200);
        $unidades = $response->viewData('unidades');
        
        foreach ($unidades as $unidad) {
            $this->assertEquals(1, $unidad->piso);
        }
    }

    public function test_IndexFiltraPorEstado()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('unidades.index', [
            'estado' => 'Finalizado'
        ]));
        
        $response->assertStatus(200);
        $unidades = $response->viewData('unidades');
        
        foreach ($unidades as $unidad) {
            $this->assertEquals('Finalizado', $unidad->estado);
        }
    }

    public function test_IndexFiltraPorOcupacion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('unidades.index', [
            'filter_ocupacion' => 'ocupada'
        ]));
        
        $response->assertStatus(200);
        $unidades = $response->viewData('unidades');
        
        foreach ($unidades as $unidad) {
            $this->assertGreaterThan(0, $unidad->personas->count());
        }
    }

    public function test_CreateMuestraFormularioCreacion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('unidades.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.unidades.create');
    }

    public function test_StoreCreaUnidadHabitacional()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $datosUnidad = [
            'numero_departamento' => 'TEST101',
            'piso' => 5,
            'estado' => 'En construccion'
        ];
        
        $response = $this->actingAs($admin)->post(route('unidades.store'), $datosUnidad);
        
        $response->assertRedirect(route('unidades.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('unidades_habitacionales', [
            'numero_departamento' => 'TEST101',
            'piso' => 5,
            'estado' => 'En construccion'
        ]);
    }

    public function test_StoreValidaDatosRequeridos()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->post(route('unidades.store'), []);
        
        $response->assertSessionHasErrors(['numero_departamento', 'piso', 'estado']);
    }

    public function test_StoreValidaUnidadUnica()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $unidadExistente = UnidadHabitacional::first();
        
        $datosUnidad = [
            'numero_departamento' => $unidadExistente->numero_departamento,
            'piso' => 2,
            'estado' => 'Finalizado'
        ];
        
        $response = $this->actingAs($admin)->post(route('unidades.store'), $datosUnidad);
        
        $response->assertSessionHasErrors(['numero_departamento']);
    }

    public function test_ShowMuestraDetallesUnidad()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $unidad = UnidadHabitacional::with('personas')->first();
        
        $response = $this->actingAs($admin)->get(route('unidades.show', $unidad->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.unidades.show');
        $response->assertViewHas('unidad');
        
        $unidadVista = $response->viewData('unidad');
        $this->assertEquals($unidad->id, $unidadVista->id);
    }

    public function test_EditMuestraFormularioEdicion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $unidad = UnidadHabitacional::first();
        
        $response = $this->actingAs($admin)->get(route('unidades.edit', $unidad->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.unidades.edit');
        $response->assertViewHas('unidad');
    }

    public function test_UpdateActualizaUnidad()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $unidad = UnidadHabitacional::first();
        
        $datosActualizados = [
            'numero_departamento' => $unidad->numero_departamento,
            'piso' => 10,
            'estado' => 'Finalizado'
        ];
        
        $response = $this->actingAs($admin)->put(route('unidades.update', $unidad->id), $datosActualizados);
        
        $response->assertRedirect(route('unidades.show', $unidad->id));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('unidades_habitacionales', [
            'id' => $unidad->id,
            'piso' => 10,
            'estado' => 'Finalizado'
        ]);
    }

    public function test_DestroyEliminaUnidadSinPersonas()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $unidad = UnidadHabitacional::create([
            'numero_departamento' => 'DELETE_TEST',
            'piso' => 99,
            'estado' => 'En construccion'
        ]);
        
        $response = $this->actingAs($admin)->delete(route('unidades.destroy', $unidad->id));
        
        $response->assertRedirect(route('unidades.index'));
        $response->assertSessionHas('success');
        
        $this->assertSoftDeleted('unidades_habitacionales', ['id' => $unidad->id]);
    }

    public function test_DestroyNoEliminaUnidadConPersonas()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $unidad = UnidadHabitacional::whereHas('personas')->first();
        
        if ($unidad) {
            $response = $this->actingAs($admin)->delete(route('unidades.destroy', $unidad->id));
            
            $response->assertRedirect();
            $response->assertSessionHas('error');
            
            $this->assertDatabaseHas('unidades_habitacionales', [
                'id' => $unidad->id,
                'deleted_at' => null
            ]);
        }
    }

    public function test_AsignarPersonaFunciona()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $unidad = UnidadHabitacional::whereDoesntHave('personas')->first();
        $persona = Persona::where('estadoRegistro', 'Aceptado')
                         ->whereNull('unidad_habitacional_id')
                         ->first();
        
        if ($unidad && $persona) {
            $response = $this->actingAs($admin)->post(
                route('unidades.asignar-persona', $unidad->id),
                ['persona_id' => $persona->id]
            );
            
            $response->assertJson(['success' => true]);
            
            $this->assertDatabaseHas('personas', [
                'id' => $persona->id,
                'unidad_habitacional_id' => $unidad->id
            ]);
        }
    }

    public function test_AsignarPersonaValidaEstado()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $unidad = UnidadHabitacional::first();
        $personaPendiente = Persona::where('estadoRegistro', 'Pendiente')
                                  ->whereNull('unidad_habitacional_id')
                                  ->first();
        
        if ($unidad && $personaPendiente) {
            $response = $this->actingAs($admin)->post(
                route('unidades.asignar-persona', $unidad->id),
                ['persona_id' => $personaPendiente->id]
            );
            
            $response->assertJson(['success' => false]);
            $response->assertStatus(422);
        }
    }

    public function test_DesasignarPersonaFunciona()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $unidad = UnidadHabitacional::whereHas('personas')->first();
        
        if ($unidad && $unidad->personas->count() > 0) {
            $persona = $unidad->personas->first();
            
            $response = $this->actingAs($admin)->delete(
                route('unidades.desasignar-persona', [$unidad->id, $persona->id])
            );
            
            $response->assertRedirect(route('unidades.show', $unidad->id));
            $response->assertSessionHas('success');
            
            $this->assertDatabaseHas('personas', [
                'id' => $persona->id,
                'unidad_habitacional_id' => null
            ]);
        }
    }

    public function test_PersonasDisponiblesRetornaJson()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('unidades.personas-disponibles'));
        
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure([
            'success',
            'personas' => [
                '*' => [
                    'id',
                    'name',
                    'apellido',
                    'estadoRegistro',
                    'user'
                ]
            ]
        ]);
    }

    public function test_SeederCreaUnidadesCorrectamente()
    {
        $unidadesFinalizadas = UnidadHabitacional::where('estado', 'Finalizado')->count();
        $this->assertGreaterThanOrEqual(10, $unidadesFinalizadas);
        
        $unidadesEnConstruccion = UnidadHabitacional::where('estado', 'En construccion')->count();
        $this->assertGreaterThanOrEqual(10, $unidadesEnConstruccion);
        
        $unidadesConPersonas = UnidadHabitacional::where('estado', 'Finalizado')
                                                ->whereHas('personas')
                                                ->count();
        $this->assertGreaterThan(0, $unidadesConPersonas);
    }

    public function test_SeederAsignaPersonasAUnidadesFinalizadas()
    {
        $unidadesFinalizadas = UnidadHabitacional::where('estado', 'Finalizado')
                                                ->with('personas')
                                                ->get();
        
        foreach ($unidadesFinalizadas as $unidad) {
            if ($unidad->personas->count() > 0) {
                foreach ($unidad->personas as $persona) {
                    $this->assertContains($persona->estadoRegistro, ['Aceptado', 'Inactivo']);
                    $this->assertEquals($unidad->id, $persona->unidad_habitacional_id);
                    $this->assertNotNull($persona->fecha_asignacion_unidad);
                }
            }
        }
    }

    public function test_UnidadesEnConstruccionNoTienenPersonasAsignadas()
    {
        $unidadesEnConstruccion = UnidadHabitacional::where('estado', 'En construccion')
                                                   ->with('personas')
                                                   ->get();
        
        foreach ($unidadesEnConstruccion as $unidad) {
            $this->assertEquals(0, $unidad->personas->count(), 
                "La unidad en construcción {$unidad->numero_departamento} no debería tener personas asignadas"
            );
        }
    }
}