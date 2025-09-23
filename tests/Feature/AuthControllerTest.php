<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\UserAdmin;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar el seeder para tener usuarios reales
        $this->seed();
    }

    public function test_MuestraFormularioLogin()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_LoginExitosoRedirigeADashboard()
    {
        // Usar el usuario admin creado por el seeder
        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => '123456',
        ]);
        
        $response->assertRedirect('dashboard');
        
        // Verificar que el usuario está autenticado
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $this->assertAuthenticatedAs($admin);
    }

    public function test_LoginFallidoMuestraError()
    {
        $response = $this->post('/login', [
            'email' => 'noexiste@correo.com',
            'password' => 'incorrecta',
        ]);
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_LogoutCierraSesionYRedirige()
    {
        // Usar el usuario admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $this->be($admin);
        
        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_DashboardMuestraVistaDashboard()
    {
        // Usar el usuario admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $this->be($admin);
        
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }
}
