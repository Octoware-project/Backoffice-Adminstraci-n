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
            PlanTrabajoSeeder::class,
            HorasMensualesSeeder::class
        ]);

    }
}
