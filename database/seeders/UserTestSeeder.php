<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\UnidadHabitacional;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserTestSeeder extends Seeder
{
    public function run()
    {
        $email = 'user@test.com';
        $password = 'password';
        $name = 'Test User';

        // Verificar si el usuario ya existe en Backoffice
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            $this->command->warn("Usuario {$email} ya existe en Backoffice.");
            // Actualizar persona con unidad si no tiene
            $persona = Persona::where('user_id', $existingUser->id)->first();
            if ($persona && !$persona->unidad_habitacional_id) {
                $unidad = UnidadHabitacional::first();
                if ($unidad) {
                    $persona->update([
                        'unidad_habitacional_id' => $unidad->id,
                        'fecha_asignacion_unidad' => now(),
                    ]);
                    $this->command->info("Unidad asignada al usuario {$email}.");
                }
            }
        } else {
            // Crear usuario en Backoffice
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Obtener la primera unidad habitacional
            $unidad = UnidadHabitacional::first();

            // Crear persona asociada con unidad asignada
            Persona::create([
                'user_id' => $user->id,
                'name' => 'Test',
                'apellido' => 'User',
                'CI' => '12345678',
                'telefono' => '099123456',
                'direccion' => 'Calle de prueba 123',
                'estadoRegistro' => 'Aceptado',
                'unidad_habitacional_id' => $unidad ? $unidad->id : null,
                'fecha_asignacion_unidad' => $unidad ? now() : null,
            ]);
        }
    }
}
