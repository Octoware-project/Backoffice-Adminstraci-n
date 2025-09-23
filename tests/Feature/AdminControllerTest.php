<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UserAdmin;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ejecutar seeders para tener datos reales
        $this->artisan('db:seed');
        
        // Obtener el usuario admin que se creó en el seeder
        $this->admin = UserAdmin::where('email', 'admin@example.com')->first();
    }

    public function test_ListarAdministradores(): void
    {
        // Autenticar como el usuario admin real
        $response = $this->actingAs($this->admin)
                         ->get('/administradores');
        $response->assertStatus(200);
    }

    public function test_CrearAdministrador(): void
    {
        $data = [
            'name' => 'Test Admin',
            'email' => 'testadmin@example.com', // Usar email diferente para evitar conflictos
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->actingAs($this->admin)
                         ->post('/administradores', $data);
        $response->assertRedirect('/administradores');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('user_admins', [
            'email' => 'testadmin@example.com',
        ]);
    }

    public function test_NoPermiteEmailDuplicado(): void
    {
        $data = [
            'name' => 'Another Admin',
            'email' => 'admin@example.com', // Usar el mismo email que ya existe
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->actingAs($this->admin)
                         ->post('/administradores', $data);
        $response->assertRedirect('/administradores');
        $response->assertSessionHas('error');
    }

    public function test_MostrarAdministrador(): void
    {
        $response = $this->actingAs($this->admin)
                         ->get("/administradores/{$this->admin->id}/edit");
        $response->assertStatus(200);
        $response->assertViewIs('administradores');
        $response->assertViewHas('admin');
    }

    public function test_ActualizarAdministrador(): void
    {
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ];
        $response = $this->actingAs($this->admin)
                         ->put("/administradores/{$this->admin->id}", $data);
        $response->assertRedirect('/administradores');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('user_admins', [
            'email' => 'updated@example.com',
        ]);
    }

    public function test_EliminarAdministrador(): void
    {
        // Crear otro admin para eliminar (no el principal)
        $adminToDelete = UserAdmin::create([
            'name' => 'Admin To Delete',
            'email' => 'delete@example.com',
            'password' => bcrypt('password123'),
        ]);
        
        $response = $this->actingAs($this->admin)
                         ->delete("/administradores/{$adminToDelete->id}");
        $response->assertRedirect('/administradores');
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('user_admins', [
            'id' => $adminToDelete->id,
        ]);
    }
}
