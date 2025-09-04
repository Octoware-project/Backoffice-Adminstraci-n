<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JuntasAsamblea;

class JuntasAsambleaSeeder extends Seeder
{
    public function run()
    {
        JuntasAsamblea::create([
            'lugar' => 'Salón Principal',
            'fecha' => '2025-09-10',
            'detalle' => 'Asamblea anual de residentes.',
        ]);
        JuntasAsamblea::create([
            'lugar' => 'Sala de Reuniones',
            'fecha' => '2025-10-05',
            'detalle' => 'Junta extraordinaria para tratar temas urgentes.',
        ]);
        JuntasAsamblea::create([
            'lugar' => 'Patio Central',
            'fecha' => '2025-11-15',
            'detalle' => 'Reunión informativa sobre nuevas normas.',
        ]);
    }
}
