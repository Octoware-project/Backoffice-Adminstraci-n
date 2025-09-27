<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanTrabajo;
use App\Models\User;
use App\Models\UnidadHabitacional;
use App\Models\Persona;

class PlanTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios existentes (del PersonaEstadoSeeder)
        $testUser = User::where('email', 'user@test.com')->first();
        $otherUsers = User::where('email', '!=', 'user@test.com')->take(3)->get();
        
        // Obtener unidades habitacionales disponibles
        $unidades = UnidadHabitacional::all();
        
        if ($testUser && $unidades->count() > 0) {
            // Asignar unidad al usuario test si tiene persona asociada
            if ($testUser->persona) {
                $testUser->persona->update([
                    'unidad_habitacional_id' => $unidades->first()->id,
                    'fecha_asignacion_unidad' => now()
                ]);
                
                // Plan de trabajo para usuario test
                PlanTrabajo::create([
                    'user_id' => $testUser->id,
                    'unidad_habitacional_id' => $unidades->first()->id,
                    'mes' => 9, // Septiembre
                    'anio' => 2025,
                    'horas_requeridas' => 40,
                ]);

                // Plan adicional para usuario test
                PlanTrabajo::create([
                    'user_id' => $testUser->id,
                    'unidad_habitacional_id' => $unidades->first()->id,
                    'mes' => 10, // Octubre
                    'anio' => 2025,
                    'horas_requeridas' => 35,
                ]);
            }
        }

        // Crear planes para otros usuarios
        foreach ($otherUsers as $index => $user) {
            if ($user->persona && $index < $unidades->count() - 1) {
                $unidad = $unidades->skip($index + 1)->first();
                
                // Asignar unidad a la persona
                $user->persona->update([
                    'unidad_habitacional_id' => $unidad->id,
                    'fecha_asignacion_unidad' => now()
                ]);
                
                PlanTrabajo::create([
                    'user_id' => $user->id,
                    'unidad_habitacional_id' => $unidad->id,
                    'mes' => 9 + $index, // Septiembre, Octubre, Noviembre
                    'anio' => 2025,
                    'horas_requeridas' => 30 + ($index * 5), // 30, 35, 40 horas
                ]);
            }
        }
    }
}