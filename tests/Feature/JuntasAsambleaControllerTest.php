<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\JuntasAsamblea;
use App\Models\UserAdmin;

class JuntasAsambleaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testIndexMuestraJuntasAsamblea()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.asamblea.index'));
        
        $response->assertStatus(200);
        $response->assertViewHas('juntas');
        
        $juntas = $response->viewData('juntas');
        $this->assertGreaterThan(0, $juntas->count());
    }

    public function testIndexFiltraPorMes()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.index', [
            'filter_mes' => 3 
        ]));
        
        $response->assertStatus(200);
        $response->assertViewHas('juntas');
        
        $juntas = $response->viewData('juntas');
        foreach ($juntas as $junta) {
            $this->assertEquals(3, \Carbon\Carbon::parse($junta->fecha)->month);
        }
    }

    public function test_IndexFiltraPorAnio()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.index', [
            'filter_anio' => 2024
        ]));
        
        $response->assertStatus(200);
        $response->assertViewHas('juntas');
        
        $juntas = $response->viewData('juntas');
        foreach ($juntas as $junta) {
            $this->assertEquals(2024, \Carbon\Carbon::parse($junta->fecha)->year);
        }
    }

    public function test_IndexOrdenaPorFechaDescPorDefecto()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.asamblea.index'));
        
        $juntas = $response->viewData('juntas');
        if ($juntas->count() > 1) {
            $fechaAnterior = null;
            foreach ($juntas as $junta) {
                if ($fechaAnterior !== null) {
                    $this->assertTrue(
                        \Carbon\Carbon::parse($junta->fecha) <= \Carbon\Carbon::parse($fechaAnterior),
                        'Las juntas deben estar ordenadas por fecha descendente'
                    );
                }
                $fechaAnterior = $junta->fecha;
            }
        }
    }

    public function test_CrearMuestraFormularioCreacion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.asamblea.create');
    }

    public function test_StoreCreaNuevaJuntaAsamblea()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $datosJunta = [
            'lugar' => 'Salón de Pruebas',
            'fecha' => '2024-12-15',
            'detalle' => 'Esta es una junta de prueba para testing'
        ];
        
        $response = $this->actingAs($admin)->post(route('admin.juntas_asamblea.store'), $datosJunta);
        
        $response->assertRedirect(route('admin.asamblea.index'));
        $response->assertSessionHas('success', 'Junta creada correctamente.');
        
        $this->assertDatabaseHas('juntas_asambleas', $datosJunta);
    }

    public function test_StoreValidaCamposRequeridos()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->post(route('admin.juntas_asamblea.store'), [
            'fecha' => '2024-12-15',
            'detalle' => 'Detalle sin lugar'
        ]);
        
        $response->assertSessionHasErrors('lugar');
        
        $response = $this->actingAs($admin)->post(route('admin.juntas_asamblea.store'), [
            'lugar' => 'Lugar sin fecha',
            'detalle' => 'Detalle sin fecha'
        ]);
        
        $response->assertSessionHasErrors('fecha');
    }

    public function test_StoreValidaFormatoFecha()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->post(route('admin.juntas_asamblea.store'), [
            'lugar' => 'Salón de Pruebas',
            'fecha' => 'fecha-invalida',
            'detalle' => 'Detalle con fecha inválida'
        ]);
        
        $response->assertSessionHasErrors('fecha');
    }

    public function test_ShowMuestraJuntaEspecifica()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $junta = JuntasAsamblea::first();
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.show', $junta->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.asamblea.show');
        $response->assertViewHas('junta', $junta);
    }

    public function test_EditarMuestraFormularioEdicion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $junta = JuntasAsamblea::first();
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.edit', $junta->id));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.asamblea.edit');
        $response->assertViewHas('junta', $junta);
    }

    public function test_UpdateActualizaJuntaAsamblea()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $junta = JuntasAsamblea::first();
        
        $datosActualizados = [
            'lugar' => 'Lugar Actualizado',
            'fecha' => '2024-12-20',
            'detalle' => 'Detalle actualizado para testing'
        ];
        
        $response = $this->actingAs($admin)->put(route('admin.juntas_asamblea.update', $junta->id), $datosActualizados);
        
        $response->assertRedirect(route('admin.asamblea.index'));
        $response->assertSessionHas('success', 'Junta actualizada correctamente.');
        
        $this->assertDatabaseHas('juntas_asambleas', array_merge(['id' => $junta->id], $datosActualizados));
    }

    public function test_UpdateValidaCamposRequeridos()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $junta = JuntasAsamblea::first();
        
        $response = $this->actingAs($admin)->put(route('admin.juntas_asamblea.update', $junta->id), [
            'lugar' => '',
            'fecha' => '2024-12-15',
            'detalle' => 'Detalle'
        ]);
        
        $response->assertSessionHasErrors('lugar');
    }

    public function test_DestroyEliminaJuntaAsamblea()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $junta = JuntasAsamblea::first();
        $juntaId = $junta->id;
        
        $response = $this->actingAs($admin)->delete(route('admin.juntas_asamblea.destroy', $junta->id));
        
        $response->assertRedirect(route('admin.asamblea.index'));
        $response->assertSessionHas('success', 'Junta eliminada correctamente.');
        
        $this->assertSoftDeleted('juntas_asambleas', ['id' => $juntaId]);
    }

    public function test_ListaAsambleaFuncionaCorrectamente()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.asamblea.index'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.asamblea.Asamblea');
        $response->assertViewHas('juntas');
    }

    public function test_UsuariosNoAutenticadosNoPuedenAcceder()
    {
        $junta = JuntasAsamblea::first();
        
        $response = $this->get(route('admin.asamblea.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('admin.juntas_asamblea.create'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('admin.juntas_asamblea.show', $junta->id));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('admin.juntas_asamblea.edit', $junta->id));
        $response->assertRedirect(route('login'));
    }

    public function test_OrdenaCorrectamentePorDiferentesCampos()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.index', [
            'sort_field' => 'lugar',
            'sort_direction' => 'asc'
        ]));
        
        $response->assertStatus(200);
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.index', [
            'sort_field' => 'fecha',
            'sort_direction' => 'desc'
        ]));
        
        $response->assertStatus(200);
        
        $response = $this->actingAs($admin)->get(route('admin.juntas_asamblea.index', [
            'sort_field' => 'campo_invalido',
            'sort_direction' => 'asc'
        ]));
        
        $response->assertStatus(200);
    }

    public function test_DetallePuedeSerNull()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $datosJunta = [
            'lugar' => 'Salón sin detalle',
            'fecha' => '2024-12-25',
            'detalle' => null
        ];
        
        $response = $this->actingAs($admin)->post(route('admin.juntas_asamblea.store'), $datosJunta);
        
        $response->assertRedirect(route('admin.asamblea.index'));
        $response->assertSessionHas('success', 'Junta creada correctamente.');
        
        $this->assertDatabaseHas('juntas_asambleas', [
            'lugar' => 'Salón sin detalle',
            'fecha' => '2024-12-25',
            'detalle' => null
        ]);
    }
}
