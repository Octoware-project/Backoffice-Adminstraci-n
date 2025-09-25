<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConfiguracionHoras;
use App\Models\User;
use App\Models\PlanTrabajo;
use App\Models\Horas_Mensuales;

class DatosPruebaSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear configuración de horas
        ConfiguracionHoras::create([
            'valor_por_hora' => 1000.00,
            'activo' => true,
            'observaciones' => 'Configuración inicial para pruebas'
        ]);

        // 2. Buscar o crear un usuario de prueba
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Usuario Prueba',
                'email' => 'prueba@test.com',
                'password' => bcrypt('password'),
                'Estado_Registro' => 'Aceptado'
            ]);
        }

        // 3. Crear un plan de trabajo
        $plan = PlanTrabajo::create([
            'user_id' => $user->id,
            'mes' => 9, // Septiembre
            'anio' => 2025,
            'horas_requeridas' => 160 // 160 horas requeridas
        ]);

        // 4. Crear registros de horas (algunas reales, algunas justificadas)
        
        // Horas reales trabajadas (80 horas)
        for ($i = 1; $i <= 10; $i++) {
            Horas_Mensuales::create([
                'email' => $user->email,
                'anio' => 2025,
                'mes' => 9,
                'dia' => $i,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => null,
            ]);
        }

        // Justificaciones con monto (equivalente a 50 horas más)
        Horas_Mensuales::create([
            'email' => $user->email,
            'anio' => 2025,
            'mes' => 9,
            'dia' => 15,
            'Cantidad_Horas' => null,
            'Motivo_Falla' => 'Enfermedad',
            'Tipo_Justificacion' => 'Médica',
            'Monto_Compensario' => 25000.00, // $25,000 = 25 horas
        ]);

        Horas_Mensuales::create([
            'email' => $user->email,
            'anio' => 2025,
            'mes' => 9,
            'dia' => 20,
            'Cantidad_Horas' => 4, // 4 horas reales + justificación
            'Motivo_Falla' => 'Trámite personal',
            'Tipo_Justificacion' => 'Personal',
            'Monto_Compensario' => 25000.00, // $25,000 = 25 horas adicionales
        ]);

        echo "✅ Datos de prueba creados:\n";
        echo "- Configuración: $1000/hora\n";
        echo "- Usuario: {$user->name} ({$user->email})\n";
        echo "- Plan: 160 horas requeridas para Sept 2025\n";
        echo "- Registros: 80h reales + ~50h justificadas = ~130h total (81%)\n";
    }
}