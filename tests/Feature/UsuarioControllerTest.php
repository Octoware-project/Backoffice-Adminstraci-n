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
        $this->seed();
    }

    public function test_IndexMuestraUsuariosAceptados()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.index');
        $response->assertViewHas('usuarios');
        
        $usuarios = $response->viewData('usuarios');
        $this->assertGreaterThan(0, $usuarios->count());
        
        foreach ($usuarios as $usuario) {
            $this->assertContains($usuario->estadoRegistro, ['Aceptado', 'Inactivo']);
        }
    }

    public function test_PendientesMuestraUsuariosPendientes()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.pendientes'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.pendientes');
        $response->assertViewHas('usuarios');
        
        $usuarios = $response->viewData('usuarios');
        $this->assertGreaterThan(0, $usuarios->count());
        
        foreach ($usuarios as $usuario) {
            $this->assertEquals('Pendiente', $usuario->estadoRegistro);
        }
    }

    public function test_AceptarUsuarioCambiaEstado()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $persona = Persona::where('estadoRegistro', 'Pendiente')->first();
        
        $response = $this->actingAs($admin)->put(route('usuarios.aceptar', $persona->id));
        $response->assertRedirect(route('usuarios.show', ['id' => $persona->id]));
        $this->assertEquals('Inactivo', $persona->fresh()->estadoRegistro);
    }

    public function test_RechazarUsuarioCambiaEstado()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
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
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $persona = Persona::where('name', 'test')->where('apellido', 'User')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.show', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.show');
        $response->assertViewHas('usuario');
    }

    public function test_EditMuestraFormularioEdicion()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $persona = Persona::where('name', 'test')->where('apellido', 'User')->first();
        
        $response = $this->actingAs($admin)->get(route('usuarios.edit', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.edit');
        $response->assertViewHas('usuario');
    }

    public function test_UpdateActualizaDatosUsuario()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        
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
        
        $personaActualizada = $persona->fresh();
        $this->assertEquals('NuevoNombre', $personaActualizada->name);
        $this->assertEquals('NuevoApellido', $personaActualizada->apellido);
        $this->assertEquals('12345678', $personaActualizada->CI);
        $this->assertEquals('123456789', $personaActualizada->telefono);
        $this->assertEquals('Nueva Direccion', $personaActualizada->direccion);
        $this->assertEquals('Aceptado', $personaActualizada->estadoRegistro);
        $this->assertEquals('nuevo@email.com', $personaActualizada->user->email);
    }

    public function test_DestroyEliminaUsuario()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        
        $response = $this->actingAs($admin)->delete(route('usuarios.destroy', $persona->id));
        $response->assertRedirect(route('usuarios.index'));
        
        $this->assertSoftDeleted('personas', ['id' => $persona->id]);
    }

    public function test_EliminadosMuestraUsuariosEliminados()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        $persona->delete();
        
        $response = $this->actingAs($admin)->get(route('usuarios.eliminados'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.eliminados');
        $response->assertViewHas('usuarios');
        
        $usuarios = $response->viewData('usuarios');
        $this->assertGreaterThan(0, $usuarios->count());
    }

    public function test_RestaurarUsuarioRestaurasoftDelete()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        
        $persona = Persona::where('name', 'Usuario')->where('apellido', 'Rechazado')->first();
        $personaId = $persona->id;
        $persona->delete();
        
        $this->assertSoftDeleted('personas', ['id' => $personaId]);
        
        $response = $this->actingAs($admin)->put(route('usuarios.restaurar', $personaId));
        $response->assertRedirect(route('usuarios.eliminados'));
        
        $this->assertDatabaseHas('personas', [
            'id' => $personaId,
            'deleted_at' => null
        ]);
    }

    public function test_RequiereAutenticacion()
    {
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
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $persona = Persona::where('estadoRegistro', 'Aceptado')->first();
        $estadoOriginal = $persona->estadoRegistro;
        
        $response = $this->actingAs($admin)->put(route('usuarios.aceptar', $persona->id));
        
        $this->assertEquals($estadoOriginal, $persona->fresh()->estadoRegistro);
    }

    public function test_RechazarUsuarioSoloSiFuesPendiente()
    {
        $admin = UserAdmin::where('email', 'admin@example.com')->first();
        $persona = Persona::where('estadoRegistro', 'Aceptado')->first();
        $estadoOriginal = $persona->estadoRegistro;
        
        $response = $this->actingAs($admin)->put(route('usuarios.rechazar', $persona->id));
        
        $this->assertEquals($estadoOriginal, $persona->fresh()->estadoRegistro);
    }
}
