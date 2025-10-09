<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PersonaEstadoSeeder extends Seeder
{
    public function run()
    {
        // Crear 10 usuarios/personas Aceptados en noviembre 2024
        $this->crearUsuariosAceptados();
        
        // Crear 5 usuarios/personas Inactivos
        $this->crearUsuariosInactivos();
        
        // Crear 10 usuarios/personas Pendientes
        $this->crearUsuariosPendientes();

        $this->command->info('Creados 25 usuarios con sus personas asociadas');
    }

    private function crearUsuariosAceptados()
    {
        // Fechas de noviembre 2024 para fecha_aceptacion
        $fechasNoviembre = [
            '2024-11-01', '2024-11-03', '2024-11-05', '2024-11-08', '2024-11-12',
            '2024-11-15', '2024-11-18', '2024-11-22', '2024-11-25', '2024-11-28'
        ];

        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Usuario Aceptado $i",
                'email' => "aceptado$i@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            Persona::create([
                'user_id' => $user->id,
                'name' => "Usuario$i",
                'apellido' => 'Aceptado',
                'CI' => '1000000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'telefono' => '+598 9' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'direccion' => "Dirección Aceptado $i, Montevideo",
                'estadoCivil' => ['Soltero', 'Casado', 'Divorciado'][rand(0, 2)],
                'genero' => ['Masculino', 'Femenino'][rand(0, 1)],
                'fechaNacimiento' => Carbon::createFromDate(1980 + $i, rand(1, 12), rand(1, 28)),
                'ocupacion' => "Ocupación Aceptado $i",
                'nacionalidad' => 'Uruguaya',
                'estadoRegistro' => 'Aceptado',
                'fecha_aceptacion' => Carbon::parse($fechasNoviembre[$i - 1]),
                'created_at' => Carbon::parse($fechasNoviembre[$i - 1]),
                'updated_at' => Carbon::parse($fechasNoviembre[$i - 1]),
            ]);
        }

        // Crear el usuario específico que buscan algunos tests
        $userTest = User::create([
            'name' => 'test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        Persona::create([
            'user_id' => $userTest->id,
            'name' => 'test',
            'apellido' => 'User',
            'CI' => '10000099',
            'telefono' => '+598 91234567',
            'direccion' => 'Dirección Test, Montevideo',
            'estadoCivil' => 'Soltero',
            'genero' => 'Masculino',
            'fechaNacimiento' => Carbon::createFromDate(1990, 5, 15),
            'ocupacion' => 'Tester',
            'nacionalidad' => 'Uruguaya',
            'estadoRegistro' => 'Aceptado',
            'fecha_aceptacion' => Carbon::parse('2024-11-30'),
        ]);
    }

    private function crearUsuariosInactivos()
    {
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => "Usuario Inactivo $i",
                'email' => "inactivo$i@example.com", 
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            Persona::create([
                'user_id' => $user->id,
                'name' => "Usuario$i",
                'apellido' => 'Inactivo',
                'CI' => '2000000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'telefono' => '+598 8' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'direccion' => "Dirección Inactivo $i, Montevideo",
                'estadoCivil' => ['Soltero', 'Casado', 'Divorciado'][rand(0, 2)],
                'genero' => ['Masculino', 'Femenino'][rand(0, 1)],
                'fechaNacimiento' => Carbon::createFromDate(1975 + $i, rand(1, 12), rand(1, 28)),
                'ocupacion' => "Ocupación Inactivo $i",
                'nacionalidad' => 'Uruguaya',
                'estadoRegistro' => 'Inactivo',
            ]);
        }
    }

    private function crearUsuariosPendientes()
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Usuario Pendiente $i",
                'email' => "pendiente$i@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            Persona::create([
                'user_id' => $user->id,
                'name' => "Usuario$i",
                'apellido' => 'Pendiente',
                'CI' => '3000000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'telefono' => '+598 7' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'direccion' => "Dirección Pendiente $i, Montevideo",
                'estadoCivil' => ['Soltero', 'Casado', 'Divorciado'][rand(0, 2)],
                'genero' => ['Masculino', 'Femenino'][rand(0, 1)],
                'fechaNacimiento' => Carbon::createFromDate(1985 + $i, rand(1, 12), rand(1, 28)),
                'ocupacion' => "Ocupación Pendiente $i",
                'nacionalidad' => 'Uruguaya',
                'estadoRegistro' => 'Pendiente',
            ]);
        }

        // Crear usuario específico que buscan los tests
        $userPendiente = User::create([
            'name' => 'Usuario Pendiente Test',
            'email' => 'pendientetest@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        Persona::create([
            'user_id' => $userPendiente->id,
            'name' => 'Usuario',
            'apellido' => 'Pendiente',
            'CI' => '30000099',
            'telefono' => '+598 70000099',
            'direccion' => 'Dirección Pendiente Test, Montevideo',
            'estadoCivil' => 'Soltero',
            'genero' => 'Masculino', 
            'fechaNacimiento' => Carbon::createFromDate(1992, 8, 20),
            'ocupacion' => 'Tester Pendiente',
            'nacionalidad' => 'Uruguaya',
            'estadoRegistro' => 'Pendiente',
        ]);

        // Crear usuario rechazado para el test de update
        $userRechazado = User::create([
            'name' => 'Usuario Rechazado Test',
            'email' => 'rechazadotest@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        Persona::create([
            'user_id' => $userRechazado->id,
            'name' => 'Usuario',
            'apellido' => 'Rechazado',
            'CI' => '40000001',
            'telefono' => '+598 60000001',
            'direccion' => 'Dirección Rechazado Test, Montevideo',
            'estadoCivil' => 'Soltero',
            'genero' => 'Femenino',
            'fechaNacimiento' => Carbon::createFromDate(1988, 3, 10),
            'ocupacion' => 'Tester Rechazado',
            'nacionalidad' => 'Uruguaya',
            'estadoRegistro' => 'Rechazado',
        ]);
    }
}