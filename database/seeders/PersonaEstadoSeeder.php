<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PersonaEstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primer usuario específico - ACEPTADO
        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'password' => Hash::make('password'),
        ]);
        $persona = Persona::factory()->aceptado()->make([
            'name' => 'test',
            'apellido' => 'User',
        ]);
        $persona->user_id = $user->id;
        $persona->save();

        // Usuario específico - PENDIENTE
        $userPendiente = User::create([
            'name' => 'Usuario Pendiente',
            'email' => 'pendiente@test.com',
            'password' => Hash::make('password'),
        ]);
        $personaPendiente = Persona::factory()->pendiente()->make([
            'name' => 'Usuario',
            'apellido' => 'Pendiente',
        ]);
        $personaPendiente->user_id = $userPendiente->id;
        $personaPendiente->save();

        // Usuario específico - RECHAZADO
        $userRechazado = User::create([
            'name' => 'Usuario Rechazado',
            'email' => 'rechazado@test.com',
            'password' => Hash::make('password'),
        ]);
        $personaRechazada = Persona::factory()->rechazado()->make([
            'name' => 'Usuario',
            'apellido' => 'Rechazado',
        ]);
        $personaRechazada->user_id = $userRechazado->id;
        $personaRechazada->save();

        // Usuario específico - INACTIVO
        $userInactivo = User::create([
            'name' => 'Usuario Inactivo',
            'email' => 'inactivo@test.com',
            'password' => Hash::make('password'),
        ]);
        $personaInactiva = Persona::factory()->inactivo()->make([
            'name' => 'Usuario',
            'apellido' => 'Inactivo',
        ]);
        $personaInactiva->user_id = $userInactivo->id;
        $personaInactiva->save();

        // 3 usuarios random aceptados
        for ($i = 0; $i < 3; $i++) {
            $user = User::factory()->create();
            $persona = Persona::factory()->aceptado()->make();
            $persona->user_id = $user->id;
            $persona->save();
        }

        // 4 usuarios random pendientes
        for ($i = 0; $i < 4; $i++) {
            $user = User::factory()->create();
            $persona = Persona::factory()->pendiente()->make();
            $persona->user_id = $user->id;
            $persona->save();
        }
    }
}
