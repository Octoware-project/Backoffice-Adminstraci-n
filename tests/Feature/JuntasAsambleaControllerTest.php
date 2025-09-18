<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\JuntasAsamblea;

class JuntasAsambleaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_lista_de_juntas()
    {
        $junta = JuntasAsamblea::factory()->create();
        $response = $this->get(route('admin.asamblea.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.asamblea.Asamblea');
        $response->assertViewHas('juntas');
        $response->assertSee($junta->lugar);
    }

    public function test_muestra_formulario_creacion()
    {
        $response = $this->get(route('admin.asamblea.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.asamblea.create');
    }

    public function test_crea_junta_correctamente()
    {
        $data = [
            'lugar' => 'Salón Principal',
            'fecha' => '2025-09-14',
            'detalle' => 'Asamblea anual',
        ];
        $response = $this->post(route('admin.asamblea.store'), $data);
        $response->assertRedirect(route('admin.asamblea.index'));
        $this->assertDatabaseHas('juntas_asambleas', [
            'lugar' => 'Salón Principal',
            'fecha' => '2025-09-14',
        ]);
    }

    public function test_muestra_formulario_edicion()
    {
        $junta = JuntasAsamblea::factory()->create();
        $response = $this->get(route('juntas_asamblea.edit', $junta->id));
        $response->assertStatus(200);
    $response->assertViewIs('admin.asamblea.edit');
        $response->assertViewHas('junta');
    }

    public function test_actualiza_junta()
    {
        $junta = JuntasAsamblea::factory()->create();
        $data = [
            'lugar' => 'Salón Secundario',
            'fecha' => '2025-10-01',
            'detalle' => 'Cambio de lugar',
        ];
        $response = $this->put(route('juntas_asamblea.update', $junta->id), $data);
        $response->assertRedirect(route('juntas_asamblea.index'));
        $this->assertDatabaseHas('juntas_asambleas', [
            'id' => $junta->id,
            'lugar' => 'Salón Secundario',
        ]);
    }

    public function test_elimina_junta()
    {
        $junta = JuntasAsamblea::factory()->create();
        $response = $this->delete(route('juntas_asamblea.destroy', $junta->id));
        $response->assertRedirect(route('juntas_asamblea.index'));
        $this->assertDatabaseMissing('juntas_asambleas', [
            'id' => $junta->id,
        ]);
    }

    public function test_muestra_detalle_junta()
    {
        $junta = JuntasAsamblea::factory()->create();
        $response = $this->get(route('admin.asamblea.show', $junta->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.asamblea.show');
        $response->assertViewHas('junta');
    }
}
