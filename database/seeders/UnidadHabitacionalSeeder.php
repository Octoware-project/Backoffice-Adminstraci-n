<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnidadHabitacional;

class UnidadHabitacionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear unidades habitacionales de prueba
        $unidades = [
            ['numero_departamento' => 'A-101', 'piso' => 1, 'estado' => 'Finalizado'],
            ['numero_departamento' => 'A-102', 'piso' => 1, 'estado' => 'Finalizado'],
            ['numero_departamento' => 'A-201', 'piso' => 2, 'estado' => 'Finalizado'],
            ['numero_departamento' => 'A-202', 'piso' => 2, 'estado' => 'En construccion'],
            ['numero_departamento' => 'B-101', 'piso' => 1, 'estado' => 'Finalizado'],
            ['numero_departamento' => 'B-102', 'piso' => 1, 'estado' => 'Finalizado'],
            ['numero_departamento' => 'B-201', 'piso' => 2, 'estado' => 'En construccion'],
            ['numero_departamento' => 'B-202', 'piso' => 2, 'estado' => 'Finalizado'],
            ['numero_departamento' => 'C-101', 'piso' => 1, 'estado' => 'Finalizado'],
            ['numero_departamento' => 'C-301', 'piso' => 3, 'estado' => 'En construccion'],
        ];

        foreach ($unidades as $unidad) {
            UnidadHabitacional::create($unidad);
        }
    }
}
