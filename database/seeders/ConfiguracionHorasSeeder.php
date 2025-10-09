<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConfiguracionHoras;
use Carbon\Carbon;

class ConfiguracionHorasSeeder extends Seeder
{
    public function run()
    {
        $configuraciones = [
            [
                'valor_por_hora' => 250.00,
                'activo' => false,
                'observaciones' => 'Configuración inicial del sistema. Valor base establecido según normativas cooperativas.',
                'created_at' => Carbon::now()->subYear()->subMonths(3), 
                'updated_at' => Carbon::now()->subYear()->subMonths(3),
            ],
            [
                'valor_por_hora' => 275.00,
                'activo' => false,
                'observaciones' => 'Ajuste por inflación y revisión de costos operativos del primer trimestre.',
                'created_at' => Carbon::now()->subYear(), 
                'updated_at' => Carbon::now()->subYear(),
            ],
            [
                'valor_por_hora' => 300.00,
                'activo' => false,
                'observaciones' => 'Incremento aprobado en asamblea ordinaria. Resolución N° 2024-001.',
                'created_at' => Carbon::now()->subMonths(9), 
                'updated_at' => Carbon::now()->subMonths(9),
            ],
            [
                'valor_por_hora' => 285.00,
                'activo' => false,
                'observaciones' => 'Ajuste temporal debido a situación económica. Medida preventiva.',
                'created_at' => Carbon::now()->subMonths(7), 
                'updated_at' => Carbon::now()->subMonths(7),
            ],
            [
                'valor_por_hora' => 320.00,
                'activo' => false,
                'observaciones' => 'Reajuste después de evaluación semestral. Aprobado por consejo directivo.',
                'created_at' => Carbon::now()->subMonths(5), 
                'updated_at' => Carbon::now()->subMonths(5),
            ],
            [
                'valor_por_hora' => 335.00,
                'activo' => false,
                'observaciones' => 'Incremento por mejoras en productividad y nuevos servicios implementados.',
                'created_at' => Carbon::now()->subMonths(3), 
                'updated_at' => Carbon::now()->subMonths(3),
            ],
            [
                'valor_por_hora' => 340.00,
                'activo' => false,
                'observaciones' => 'Ajuste menor para equiparar con cooperativas similares del sector.',
                'created_at' => Carbon::now()->subMonths(2), 
                'updated_at' => Carbon::now()->subMonths(2),
            ],
            [
                'valor_por_hora' => 350.00,
                'activo' => true, 
                'observaciones' => 'Valor actual vigente. Establecido tras revisión mensual de indicadores económicos.',
                'created_at' => Carbon::now()->subMonth(), 
                'updated_at' => Carbon::now()->subMonth(),
            ]
        ];


        foreach ($configuraciones as $config) {
            ConfiguracionHoras::create($config);
        }


        ConfiguracionHoras::create([
            'valor_por_hora' => 345.00,
            'activo' => false,
            'observaciones' => 'Configuración de prueba para testing del sistema.',
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now()->subDays(15),
        ]);

        ConfiguracionHoras::create([
            'valor_por_hora' => 355.00,
            'activo' => false,
            'observaciones' => 'Configuración de prueba adicional para validar funcionalidades.',
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10),
        ]);
    }
}