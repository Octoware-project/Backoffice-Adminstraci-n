<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanTrabajo;
use App\Models\User;

class PlanTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios existentes (del PersonaEstadoSeeder)
        $testUser = User::where('email', 'user@test.com')->first();
        $otherUsers = User::where('email', '!=', 'user@test.com')->take(3)->get();
        
        if ($testUser) {
            // Plan de trabajo para usuario test
            PlanTrabajo::create([
                'user_id' => $testUser->id,
                'mes' => 9, // Septiembre
                'anio' => 2025,
                'horas_requeridas' => 40,
            ]);

            // Plan adicional para usuario test
            PlanTrabajo::create([
                'user_id' => $testUser->id,
                'mes' => 10, // Octubre
                'anio' => 2025,
                'horas_requeridas' => 35,
            ]);
        }

        // Crear planes para otros usuarios
        foreach ($otherUsers as $index => $user) {
            PlanTrabajo::create([
                'user_id' => $user->id,
                'mes' => 9 + $index, // Septiembre, Octubre, Noviembre
                'anio' => 2025,
                'horas_requeridas' => 30 + ($index * 5), // 30, 35, 40 horas
            ]);
        }
    }
}