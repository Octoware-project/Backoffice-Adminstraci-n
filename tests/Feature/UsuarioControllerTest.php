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
        $response->assertViewIs('admin.usuarios.index');
        $response->assertViewHas(['pendientes', 'aceptados', 'rechazados', 'inactivos']);
        
        // Verificar que hay datos de cada estado (creados por el seeder)
        $pendientes = $response->viewData('pendientes');
        $aceptados = $response->viewData('aceptados');
        $rechazados = $response->viewData('rechazados');
        $inactivos = $response->viewData('inactivos');
        
        $this->assertGreaterThan(0, $pendientes->count());
        $this->assertGreaterThan(0, $aceptados->count());
        $this->assertGreaterThan(0, $rechazados->count());
        $this->assertGreaterThan(0, $inactivos->count());
    }

    public function test_AceptarUsuarioCambiaEstado()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar persona pendiente real del seeder
        $persona = Persona::where('estadoRegistro', 'Pendiente')->first();
        
        $response = $this->actingAs($admin)->put(route('usuarios.aceptar', $persona->id));
        $response->assertRedirect(route('usuarios.show', ['id' => $persona->id]));
        $this->assertEquals('Inactivo', $persona->fresh()->estadoRegistro);
    }

    public function test_RechazarUsuarioCambiaEstado()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar una persona pendiente específica del seeder
        $persona = Persona::where('estadoRegistro', 'Pendiente')
                         ->where('name', 'Usuario')
                         ->where('apellido', 'Pendiente')
                         ->first();
        
        $response = $this->actingAs($admin)->put(route('usuarios.rechazar', $persona->id));
        $response->assertRedirect(route('usuarios.index'));
        $this->assertEquals('Rechazado', $persona->fresh()->estadoRegistro);
    }

    public function test_ShowMuestraUsuario()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar persona real del seeder
        $persona = Persona::where('name', 'test')->where('apellido', 'User')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.show', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.show');
        $response->assertViewHas('usuario');
    }

    public function test_EditMuestraFormularioEdicion()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar persona real del seeder
        $persona = Persona::where('name', 'test')->where('apellido', 'User')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.edit', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.edit');
        $response->assertViewHas('usuario');
    }

    public function test_UpdateActualizaDatosUsuario()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar persona rechazada real del seeder (para no afectar otros tests)
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        
        $data = [
            'nombre' => 'NuevoNombre',
            'apellido' => 'NuevoApellido',
            'CI' => '12345678',
            'telefono' => '123456789', // Usar nombre correcto de la migración
            'direccion' => 'Nueva Direccion', // Usar nombre correcto de la migración
            'estadoRegistro' => 'Aceptado', // Corregir nombre del campo
            'email' => 'nuevo@email.com',
        ];
        
        $response = $this->actingAs($admin)->put(route('usuarios.update', $persona->id), $data);
        $response->assertRedirect(route('usuarios.show', $persona->id));
        
        // Verificar los cambios
        $personaActualizada = $persona->fresh();
        $this->assertEquals('NuevoNombre', $personaActualizada->name);
        $this->assertEquals('NuevoApellido', $personaActualizada->apellido);
        $this->assertEquals('12345678', $personaActualizada->CI);
        $this->assertEquals('123456789', $personaActualizada->telefono); // Usar nombre correcto
        $this->assertEquals('Nueva Direccion', $personaActualizada->direccion); // Usar nombre correcto
        $this->assertEquals('Aceptado', $personaActualizada->estadoRegistro); // Corregir nombre del campo
        $this->assertEquals('nuevo@email.com', $personaActualizada->user->email);
    }
}
