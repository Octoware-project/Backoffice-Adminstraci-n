<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Persona;
use App\Models\User;
use App\Models\UserAdmin;

class UsuarioControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ejecutar los seeders para tener datos reales
        $this->seed();
    }

    public function test_IndexMuestraListasPorEstado()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.index'));
        $response->assertStatus(200);
        $response->assertViewIs('usuarios.index');
        $response->assertViewHas('usuarios');
        
        // Verificar que hay usuarios (aceptados e inactivos)
        $usuarios = $response->viewData('usuarios');
        $this->assertGreaterThan(0, $usuarios->count());
    }

    public function test_AceptarUsuarioCambiaEstado()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Crear un usuario para el test
        $user = User::create([
            'name' => 'Usuario Pendiente',
            'email' => 'pendiente@test.com',
            'password' => bcrypt('password123'),
        ]);
        
        $persona = Persona::create([
            'user_id' => $user->id,
            'name' => 'Usuario',
            'apellido' => 'Pendiente Test',
            'CI' => '87654321',
            'telefono' => '098765432',
            'direccion' => 'Direccion Test',
            'estadoRegistro' => 'Pendiente',
        ]);
        
        $response = $this->actingAs($admin)->put(route('usuarios.aceptar', $persona->id));
        $response->assertRedirect(route('usuarios.show', ['id' => $persona->id]));
        $this->assertEquals('Inactivo', $persona->fresh()->estadoRegistro);
    }

    public function test_RechazarUsuarioCambiaEstado()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Crear un usuario para el test
        $user = User::create([
            'name' => 'Usuario Para Rechazar',
            'email' => 'rechazar@test.com',
            'password' => bcrypt('password123'),
        ]);
        
        $persona = Persona::create([
            'user_id' => $user->id,
            'name' => 'Usuario',
            'apellido' => 'Para Rechazar',
            'CI' => '11223344',
            'telefono' => '099887766',
            'direccion' => 'Direccion Test',
            'estadoRegistro' => 'Pendiente',
        ]);
        
        $response = $this->actingAs($admin)->put(route('usuarios.rechazar', $persona->id));
        $response->assertRedirect(route('usuarios.index'));
        $this->assertEquals('Rechazado', $persona->fresh()->estadoRegistro);
    }

    public function test_ShowMuestraUsuario()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Crear un usuario para el test
        $user = User::create([
            'name' => 'Test Show',
            'email' => 'show@test.com',
            'password' => bcrypt('password123'),
        ]);
        
        $persona = Persona::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'apellido' => 'Show',
            'CI' => '55667788',
            'telefono' => '091122334',
            'direccion' => 'Direccion Test',
            'estadoRegistro' => 'Aceptado',
        ]);
        
        $response = $this->actingAs($admin)->get(route('usuarios.show', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('usuarios.show');
        $response->assertViewHas('usuario');
    }

    public function test_EditMuestraFormularioEdicion()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Crear un usuario para el test
        $user = User::create([
            'name' => 'Test Edit',
            'email' => 'edit@test.com',
            'password' => bcrypt('password123'),
        ]);
        
        $persona = Persona::create([
            'user_id' => $user->id,
            'name' => 'Test',
            'apellido' => 'Edit',
            'CI' => '99887766',
            'telefono' => '092233445',
            'direccion' => 'Direccion Test',
            'estadoRegistro' => 'Aceptado',
        ]);
        
        $response = $this->actingAs($admin)->get(route('usuarios.edit', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('usuarios.edit');
        $response->assertViewHas('usuario');
    }

    public function test_UpdateActualizaDatosUsuario()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Crear un usuario para el test
        $user = User::create([
            'name' => 'Usuario Antiguo',
            'email' => 'update@test.com',
            'password' => bcrypt('password123'),
        ]);
        
        $persona = Persona::create([
            'user_id' => $user->id,
            'name' => 'Usuario',
            'apellido' => 'Antiguo',
            'CI' => '11111111',
            'telefono' => '099999999',
            'direccion' => 'Direccion Antigua',
            'estadoRegistro' => 'Aceptado',
        ]);
        
        $data = [
            'nombre' => 'NuevoNombre',
            'apellido' => 'NuevoApellido',
            'CI' => '12345678',
            'telefono' => '123456789',
            'direccion' => 'Nueva Direccion',
            'estadoRegistro' => 'Aceptado',
            'email' => 'nuevo@email.com',
        ];
        
        $response = $this->actingAs($admin)->put(route('usuarios.update', $persona->id), $data);
        $response->assertRedirect(route('usuarios.show', $persona->id));
        
        // Verificar los cambios
        $personaActualizada = $persona->fresh();
        $this->assertEquals('NuevoNombre', $personaActualizada->name);
        $this->assertEquals('NuevoApellido', $personaActualizada->apellido);
        $this->assertEquals('12345678', $personaActualizada->CI);
        $this->assertEquals('123456789', $personaActualizada->telefono);
        $this->assertEquals('Nueva Direccion', $personaActualizada->direccion);
        $this->assertEquals('Aceptado', $personaActualizada->estadoRegistro);
        $this->assertEquals('nuevo@email.com', $personaActualizada->user->email);
    }
}
