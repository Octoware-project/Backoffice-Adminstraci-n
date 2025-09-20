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
            HorasMensualesSeeder::class
        ]);
        

        Client::create([
            'id' => 1,
            'name' => 'Tests',
            'secret' => "wsBa0mp4jwSTYssUGHX5xoqD9IC0X95Gfpg0w3uY",
            'redirect' => 'http://localhost',
            'provider' => 'users',
            'personal_access_client' => false,
            'password_client' => true,
            'revoked' => false
        ]);
    }
}
