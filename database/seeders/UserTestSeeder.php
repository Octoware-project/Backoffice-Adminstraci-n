<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
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
        } else {
            // Crear usuario en Backoffice
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Crear persona asociada
            Persona::create([
                'user_id' => $user->id,
                'name' => 'Test',
                'apellido' => 'User',
                'CI' => '12345678',
                'telefono' => '099123456',
                'direccion' => 'Calle de prueba 123',
                'estadoRegistro' => 'Aceptado',
            ]);
        }
    }
}
