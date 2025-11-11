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
        // Ejecutar los seeders para tener datos reales
        $this->seed();
        
        // Autenticar con usuario admin para los tests
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $this->be($admin);
    }

    public function test_MuestraListaDeJuntas()
    {
        // Crear una junta para el test
        $junta = JuntasAsamblea::create([
            'lugar' => 'Salón Principal',
            'fecha' => '2025-12-15',
            'detalle' => 'Asamblea general ordinaria',
        ]);
        
        $response = $this->get(route('admin.asamblea.index'));
        $response->assertStatus(200);
        $response->assertViewIs('asamblea.Asamblea');
        $response->assertViewHas('juntas');
        $response->assertSee($junta->lugar);
    }

    public function test_MuestraFormularioCreacion()
    {
        $response = $this->get(route('admin.juntas_asamblea.create'));
        $response->assertStatus(200);
        $response->assertViewIs('asamblea.create');
    }

    public function test_CreaJuntaCorrectamente()
    {
        // Datos realistas para crear una nueva junta
        $data = [
            'lugar' => 'Auditorio Municipal',
            'fecha' => '2025-12-20',
            'detalle' => 'Asamblea de fin de año para balance anual',
        ];
        
        $response = $this->post(route('admin.juntas_asamblea.store'), $data);
        $response->assertRedirect(route('admin.asamblea.index'));
        $this->assertDatabaseHas('juntas_asambleas', [
            'lugar' => 'Auditorio Municipal',
            'fecha' => '2025-12-20',
            'detalle' => 'Asamblea de fin de año para balance anual',
        ]);
    }

    public function test_MuestraFormularioEdicion()
    {
        // Crear una junta para el test
        $junta = JuntasAsamblea::create([
            'lugar' => 'Sala de Reuniones',
            'fecha' => '2025-12-10',
            'detalle' => 'Reunión de consorcio',
        ]);
        
        $response = $this->get(route('admin.juntas_asamblea.edit', $junta->id));
        $response->assertStatus(200);
        $response->assertViewIs('asamblea.edit');
        $response->assertViewHas('junta');
    }

    public function test_ActualizaJunta()
    {
        // Crear una junta para el test
        $junta = JuntasAsamblea::create([
            'lugar' => 'Patio Central',
            'fecha' => '2025-11-15',
            'detalle' => 'Reunión informativa',
        ]);
        
        $data = [
            'lugar' => 'Patio Central - Actualizado',
            'fecha' => '2025-11-20',
            'detalle' => 'Reunión informativa actualizada con nuevos temas',
        ];
        
        $response = $this->put(route('admin.juntas_asamblea.update', $junta->id), $data);
        $response->assertRedirect(route('admin.asamblea.index'));
        $this->assertDatabaseHas('juntas_asambleas', [
            'id' => $junta->id,
            'lugar' => 'Patio Central - Actualizado',
            'fecha' => '2025-11-20',
        ]);
    }

    public function test_EliminaJunta()
    {
        // Crear una junta específica para eliminar (para no afectar otras pruebas)
        $junta = JuntasAsamblea::create([
            'lugar' => 'Sala Temporal',
            'fecha' => '2025-12-31',
            'detalle' => 'Junta temporal para test de eliminación',
        ]);
        
        $response = $this->delete(route('admin.juntas_asamblea.destroy', $junta->id));
        $response->assertRedirect(route('admin.asamblea.index'));
        $this->assertDatabaseMissing('juntas_asambleas', [
            'id' => $junta->id,
            'deleted_at' => null, // Verificar que fue soft deleted
        ]);
    }

    public function test_MuestraDetalleJunta()
    {
        // Crear una junta para el test
        $junta = JuntasAsamblea::create([
            'lugar' => 'Salón Principal',
            'fecha' => '2025-12-01',
            'detalle' => 'Asamblea extraordinaria',
        ]);
        
        $response = $this->get(route('admin.juntas_asamblea.show', $junta->id));
        $response->assertStatus(200);
        $response->assertViewIs('asamblea.show');
        $response->assertViewHas('junta');
    }
}
