<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Persona;
use App\Models\User;

class UsuarioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_muestra_listas_por_estado()
    {
        $user = User::factory()->create();
        Persona::factory()->create(['estadoRegistro' => 'Pendiente', 'user_id' => $user->id]);
        Persona::factory()->create(['estadoRegistro' => 'Aceptado', 'user_id' => $user->id]);
        Persona::factory()->create(['estadoRegistro' => 'Rechazado', 'user_id' => $user->id]);
        Persona::factory()->create(['estadoRegistro' => 'Inactivo', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->get(route('usuarios.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.index');
        $response->assertViewHas(['pendientes', 'aceptados', 'rechazados', 'inactivos']);
    }

    public function test_aceptar_usuario_cambia_estado()
    {
        $user = User::factory()->create();
        $persona = Persona::factory()->create(['estadoRegistro' => 'Pendiente', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->put(route('usuarios.aceptar', $persona->id));
        $response->assertRedirect(route('usuarios.show', ['id' => $persona->id]));
        $this->assertEquals('Inactivo', $persona->fresh()->estadoRegistro);
    }

    public function test_rechazar_usuario_cambia_estado()
    {
        $user = User::factory()->create();
        $persona = Persona::factory()->create(['estadoRegistro' => 'Pendiente', 'user_id' => $user->id]);
        $response = $this->actingAs($user)->put(route('usuarios.rechazar', $persona->id));
        $response->assertRedirect(route('usuarios.index'));
        $this->assertEquals('Rechazado', $persona->fresh()->estadoRegistro);
    }

    public function test_show_muestra_usuario()
    {
        $user = User::factory()->create();
        $persona = Persona::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->get(route('usuarios.show', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.show');
        $response->assertViewHas('usuario');
    }

    public function test_edit_muestra_formulario_edicion()
    {
        $user = User::factory()->create();
        $persona = Persona::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->get(route('usuarios.edit', $persona->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.usuarios.edit');
        $response->assertViewHas('usuario');
    }

    public function test_update_actualiza_datos_usuario()
    {
        $user = User::factory()->create();
        $persona = Persona::factory()->create(['user_id' => $user->id]);
        $data = [
            'nombre' => 'NuevoNombre',
            'apellido' => 'NuevoApellido',
            'CI' => '12345678',
            'Telefono' => '123456789',
            'Direccion' => 'Nueva Direccion',
            'Estado_Registro' => 'Aceptado',
            'email' => 'nuevo@email.com',
        ];
        $response = $this->actingAs($user)->put(route('usuarios.update', $persona->id), $data);
        $response->assertRedirect(route('usuarios.show', $persona->id));
        $this->assertEquals('NuevoNombre', $persona->fresh()->name);
        $this->assertEquals('NuevoApellido', $persona->fresh()->apellido);
        $this->assertEquals('12345678', $persona->fresh()->CI);
        $this->assertEquals('123456789', $persona->fresh()->Telefono);
        $this->assertEquals('Nueva Direccion', $persona->fresh()->Direccion);
        $this->assertEquals('Aceptado', $persona->fresh()->Estado_Registro);
        $this->assertEquals('nuevo@email.com', $persona->fresh()->user->email);
    }
}
