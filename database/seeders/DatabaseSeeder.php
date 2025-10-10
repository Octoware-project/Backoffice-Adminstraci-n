<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Laravel\Passport\Client;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            UserAdminSeeder::class,
            PersonaEstadoSeeder::class,
            ConfiguracionHorasSeeder::class,
            UnidadHabitacionalSeeder::class,
            FacturasSeeder::class,
            PlanTrabajoSeeder::class,
            JuntasAsambleaSeeder::class,
        ]);

    }
}
