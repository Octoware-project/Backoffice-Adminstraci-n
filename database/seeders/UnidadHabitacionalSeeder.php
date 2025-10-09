<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnidadHabitacional;
use App\Models\Persona;
use Carbon\Carbon;

class UnidadHabitacionalSeeder extends Seeder
{
    public function run()
    {
        $this->crearUnidadesFinalizadas();
        
        $this->crearUnidadesEnConstruccion();
    }

    private function crearUnidadesFinalizadas()
    {
        $personasAceptadas = Persona::where('estadoRegistro', 'Aceptado')
            ->whereNull('unidad_habitacional_id')
            ->take(10)
            ->get();

        if ($personasAceptadas->count() < 10) {
            throw new \Exception('No hay suficientes personas aceptadas disponibles para asignar a las unidades finalizadas.');
        }

        $unidadesFinalizadas = [
            ['numero' => 'A101', 'piso' => 1],
            ['numero' => 'A102', 'piso' => 1],
            ['numero' => 'A201', 'piso' => 2],
            ['numero' => 'A202', 'piso' => 2],
            ['numero' => 'B101', 'piso' => 1],
            ['numero' => 'B102', 'piso' => 1],
            ['numero' => 'B201', 'piso' => 2],
            ['numero' => 'B202', 'piso' => 2],
            ['numero' => 'C101', 'piso' => 1],
            ['numero' => 'C102', 'piso' => 1],
        ];

        foreach ($unidadesFinalizadas as $index => $unidadData) {

            $unidad = UnidadHabitacional::create([
                'numero_departamento' => $unidadData['numero'],
                'piso' => $unidadData['piso'],
                'estado' => 'Finalizado',
                'created_at' => Carbon::now()->subMonths(rand(6, 18)),
                'updated_at' => Carbon::now()->subMonths(rand(1, 5)),
            ]);

            $persona = $personasAceptadas[$index];
            $persona->update([
                'unidad_habitacional_id' => $unidad->id,
                'fecha_asignacion_unidad' => Carbon::now()->subMonths(rand(1, 6)),
            ]);
        }

        $unidadesFamiliares = [
            ['numero' => 'D301', 'piso' => 3],
            ['numero' => 'D302', 'piso' => 3], 
        ];

        $personasRestantes = Persona::where('estadoRegistro', 'Aceptado')
            ->whereNull('unidad_habitacional_id')
            ->take(4)
            ->get();

        if ($personasRestantes->count() >= 4) {
            foreach ($unidadesFamiliares as $index => $unidadData) {
                $unidad = UnidadHabitacional::create([
                    'numero_departamento' => $unidadData['numero'],
                    'piso' => $unidadData['piso'],
                    'estado' => 'Finalizado',
                    'created_at' => Carbon::now()->subMonths(rand(8, 12)),
                    'updated_at' => Carbon::now()->subMonths(rand(1, 3)),
                ]);

                for ($i = 0; $i < 2; $i++) {
                    if (isset($personasRestantes[$index * 2 + $i])) {
                        $persona = $personasRestantes[$index * 2 + $i];
                        $persona->update([
                            'unidad_habitacional_id' => $unidad->id,
                            'fecha_asignacion_unidad' => Carbon::now()->subMonths(rand(1, 4)),
                        ]);
                    }
                }
            }
        }
    }

    private function crearUnidadesEnConstruccion()
    {
        $unidadesEnConstruccion = [
            ['numero' => 'E101', 'piso' => 1],
            ['numero' => 'E102', 'piso' => 1],
            ['numero' => 'E201', 'piso' => 2],
            ['numero' => 'E202', 'piso' => 2],
            ['numero' => 'F101', 'piso' => 1],
            ['numero' => 'F102', 'piso' => 1],
            ['numero' => 'F201', 'piso' => 2],
            ['numero' => 'F202', 'piso' => 2],
            ['numero' => 'G101', 'piso' => 1],
            ['numero' => 'G102', 'piso' => 1],
        ];

        foreach ($unidadesEnConstruccion as $unidadData) {
            UnidadHabitacional::create([
                'numero_departamento' => $unidadData['numero'],
                'piso' => $unidadData['piso'],
                'estado' => 'En construccion',
                'created_at' => Carbon::now()->subMonths(rand(1, 8)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        $unidadesSuperiores = [
            ['numero' => 'A301', 'piso' => 3],
            ['numero' => 'A302', 'piso' => 3],
            ['numero' => 'B301', 'piso' => 3],
            ['numero' => 'B302', 'piso' => 3],
            ['numero' => 'C301', 'piso' => 3],
        ];

        foreach ($unidadesSuperiores as $unidadData) {
            UnidadHabitacional::create([
                'numero_departamento' => $unidadData['numero'],
                'piso' => $unidadData['piso'],
                'estado' => 'En construccion',
                'created_at' => Carbon::now()->subWeeks(rand(1, 12)),
                'updated_at' => Carbon::now()->subDays(rand(1, 15)),
            ]);
        }
    }
}