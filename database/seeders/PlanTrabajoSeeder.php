<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanTrabajo;
use App\Models\User;
use Carbon\Carbon;

class PlanTrabajoSeeder extends Seeder
{
    public function run()
    {
        $usuarios = User::whereHas('persona', function ($query) {
            $query->where('estadoRegistro', 'Aceptado');
        })->take(10)->get();

        if ($usuarios->count() < 10) {
            throw new \Exception('No hay suficientes usuarios aceptados. Asegúrate de ejecutar PersonaEstadoSeeder primero.');
        }

        $horasRequeridas = [40, 35, 45, 30, 50, 38, 42, 36, 48, 44];
        
        foreach ($usuarios as $index => $usuario) {
            PlanTrabajo::create([
                'user_id' => $usuario->id,
                'unidad_habitacional_id' => $usuario->persona->unidad_habitacional_id, 
                'mes' => 11, 
                'anio' => 2024,
                'horas_requeridas' => $horasRequeridas[$index],
                'created_at' => Carbon::parse('2024-10-28')->addDays($index), 
                'updated_at' => Carbon::parse('2024-10-28')->addDays($index),
            ]);
        }

        $usuariosAdicionales = User::whereHas('persona', function ($query) {
            $query->where('estadoRegistro', 'Aceptado');
        })->skip(10)->take(5)->get();

        foreach ($usuariosAdicionales as $index => $usuario) {
            PlanTrabajo::create([
                'user_id' => $usuario->id,
                'unidad_habitacional_id' => $usuario->persona->unidad_habitacional_id,
                'mes' => 12,
                'anio' => 2024,
                'horas_requeridas' => 40,
                'created_at' => Carbon::parse('2024-11-25')->addDays($index),
                'updated_at' => Carbon::parse('2024-11-25')->addDays($index),
            ]);
        }

        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser) {
            PlanTrabajo::create([
                'user_id' => $testUser->id,
                'unidad_habitacional_id' => $testUser->persona->unidad_habitacional_id,
                'mes' => 11,
                'anio' => 2024,
                'horas_requeridas' => 40,
                'created_at' => Carbon::parse('2024-10-15'),
                'updated_at' => Carbon::parse('2024-10-15'),
            ]);
        }
    }
}