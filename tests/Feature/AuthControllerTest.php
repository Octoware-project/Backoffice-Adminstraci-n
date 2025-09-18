<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\UserAdmin;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_formulario_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_login_exitoso_redirige_a_dashboard()
    {
        $admin = UserAdmin::factory()->create([
            'email' => 'admin@correo.com',
            'password' => bcrypt('password123'),
        ]);
        $response = $this->post('/login', [
            'email' => 'admin@correo.com',
            'password' => 'password123',
        ]);
        $response->assertRedirect('dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_fallido_muestra_error()
    {
        $response = $this->post('/login', [
            'email' => 'noexiste@correo.com',
            'password' => 'incorrecta',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_cierra_sesion_y_redirige()
    {
        $admin = UserAdmin::factory()->create([
            'email' => 'admin@correo.com',
            'password' => bcrypt('password123'),
        ]);
        $this->be($admin);
        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_dashboard_muestra_vista_dashboard()
    {
        $admin = UserAdmin::factory()->create();
        $this->be($admin);
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }
}
