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

    public function test_IndexMuestraUsuariosAceptados()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.index');
        $response->assertViewHas('usuarios');
        
        // Verificar que hay usuarios aceptados e inactivos (según lógica del controlador)
        $usuarios = $response->viewData('usuarios');
        $this->assertGreaterThan(0, $usuarios->count());
        
        // Verificar que solo trae usuarios aceptados e inactivos
        foreach ($usuarios as $usuario) {
            $this->assertContains($usuario->estadoRegistro, ['Aceptado', 'Inactivo']);
        }
    }

    public function test_PendientesMuestraUsuariosPendientes()
    {
        // Usar admin real del seeder para autenticación
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.pendientes'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.pendientes');
        $response->assertViewHas('usuarios');
        
        // Verificar que hay usuarios pendientes
        $usuarios = $response->viewData('usuarios');
        $this->assertGreaterThan(0, $usuarios->count());
        
        // Verificar que solo trae usuarios pendientes
        foreach ($usuarios as $usuario) {
            $this->assertEquals('Pendiente', $usuario->estadoRegistro);
        }
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
        $response->assertRedirect(route('usuarios.show', $persona->id));
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

    public function test_DestroyEliminaUsuario()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar persona rechazada para no afectar otros tests
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        
        $response = $this->actingAs($admin)->delete(route('usuarios.destroy', $persona->id));
        $response->assertRedirect(route('usuarios.index'));
        
        // Verificar soft delete
        $this->assertSoftDeleted('personas', ['id' => $persona->id]);
    }

    public function test_EliminadosMuestraUsuariosEliminados()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Primero eliminar un usuario
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        $persona->delete();
        
        $response = $this->actingAs($admin)->get(route('usuarios.eliminados'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.eliminados');
        $response->assertViewHas('usuarios');
        
        // Verificar que hay usuarios eliminados
        $usuarios = $response->viewData('usuarios');
        $this->assertGreaterThan(0, $usuarios->count());
    }

    public function test_RestaurarUsuarioRestaurasoftDelete()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        // Primero eliminar un usuario
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        $personaId = $persona->id;
        $persona->delete();
        
        // Verificar que está eliminado
        $this->assertSoftDeleted('personas', ['id' => $personaId]);
        
        // Restaurar usuario
        $response = $this->actingAs($admin)->put(route('usuarios.restaurar', $personaId));
        $response->assertRedirect(route('usuarios.eliminados'));
        
        // Verificar que ya no está eliminado
        $this->assertDatabaseHas('personas', [
            'id' => $personaId,
            'deleted_at' => null
        ]);
    }

    public function test_RequiereAutenticacion()
    {
        // Probar que las rutas requieren autenticación
        $persona = Persona::first();
        
        $response = $this->get(route('usuarios.index'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('usuarios.pendientes'));
        $response->assertRedirect(route('login'));
        
        $response = $this->get(route('usuarios.show', $persona->id));
        $response->assertRedirect(route('login'));
        
        $response = $this->put(route('usuarios.aceptar', $persona->id));
        $response->assertRedirect(route('login'));
    }

    public function test_AceptarUsuarioSoloSiFuesPendiente()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar persona que ya está aceptada
        $persona = Persona::where('estadoRegistro', 'Aceptado')->first();
        $estadoOriginal = $persona->estadoRegistro;
        
        $response = $this->actingAs($admin)->put(route('usuarios.aceptar', $persona->id));
        
        // El estado no debe cambiar si ya no es pendiente
        $this->assertEquals($estadoOriginal, $persona->fresh()->estadoRegistro);
    }

    public function test_RechazarUsuarioSoloSiFuesPendiente()
    {
        // Usar admin real del seeder
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        // Usar persona que ya está aceptada
        $persona = Persona::where('estadoRegistro', 'Aceptado')->first();
        $estadoOriginal = $persona->estadoRegistro;
        
        $response = $this->actingAs($admin)->put(route('usuarios.rechazar', $persona->id));
        
        // El estado no debe cambiar si ya no es pendiente
        $this->assertEquals($estadoOriginal, $persona->fresh()->estadoRegistro);
    }
}
