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
            JuntasAsambleaSeeder::class,
            Facturas_y_Pagos::class,
            PersonaEstadoSeeder::class,
            UnidadHabitacionalSeeder::class, // Debe ir antes de PlanTrabajoSeeder
            PlanTrabajoSeeder::class,
            HorasMensualesSeeder::class
        ]);

    }
}
