<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\UserAdmin;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_lista_de_administradores()
    {
        $admin = UserAdmin::factory()->create();
        $response = $this->get(route('admin.list'));
        $response->assertStatus(200);
        $response->assertViewIs('administradores');
        $response->assertViewHas('admins');
        $response->assertSee($admin->name);
    }

    public function test_crea_administrador_correctamente()
    {
        $data = [
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->post(route('admin.store'), $data);
        $response->assertRedirect(route('admin.list'));
        $this->assertDatabaseHas('user_admins', [
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
        ]);
    }

    public function test_no_permite_email_duplicado()
    {
        $admin = UserAdmin::factory()->create(['email' => 'admin@example.com']);
        $data = [
            'name' => 'Another Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        $response = $this->post(route('admin.store'), $data);
        $response->assertRedirect(route('admin.list'));
        $response->assertSessionHas('error');
    }

    public function test_muestra_formulario_edicion()
    {
        $admin = UserAdmin::factory()->create();
        $response = $this->get(route('admin.edit', $admin->id));
        $response->assertStatus(200);
        $response->assertViewIs('administradores');
        $response->assertViewHas('admin');
    }

    public function test_actualiza_administrador()
    {
        $admin = UserAdmin::factory()->create();
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'password' => '',
            'password_confirmation' => '',
        ];
        $response = $this->put(route('admin.update', $admin->id), $data);
        $response->assertRedirect(route('admin.list'));
        $this->assertDatabaseHas('user_admins', [
            'id' => $admin->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_elimina_administrador()
    {
        $admin = UserAdmin::factory()->create();
        $response = $this->delete(route('admin.destroy', $admin->id));
        $response->assertRedirect(route('admin.list'));
        $this->assertDatabaseMissing('user_admins', [
            'id' => $admin->id,
        ]);
    }
}
