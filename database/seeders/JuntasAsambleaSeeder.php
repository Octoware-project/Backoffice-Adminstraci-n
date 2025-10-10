<?php

namespace Database\Seeders;

use App\Models\JuntasAsamblea;
use Illuminate\Database\Seeder;

class JuntasAsambleaSeeder extends Seeder
{
    public function run(): void
    {
        $juntasAsamblea = [
            [
                'lugar' => 'Salón de Usos Múltiples - Edificio A',
                'fecha' => '2024-03-15',
                'detalle' => 'Asamblea ordinaria para la aprobación del presupuesto anual 2024. Se tratarán temas relacionados con las expensas comunes, proyectos de mejora en áreas comunes y elección de nuevos miembros del consejo de administración.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lugar' => 'Auditorio Central - Torre Principal',
                'fecha' => '2024-06-20',
                'detalle' => 'Asamblea extraordinaria convocada para discutir la implementación del nuevo sistema de seguridad. Se presentarán las propuestas de diferentes proveedores y se votará por la mejor opción para la comunidad.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lugar' => 'Sala de Reuniones - Planta Baja',
                'fecha' => '2024-09-10',
                'detalle' => 'Reunión para tratar el mantenimiento de la piscina y áreas recreativas. Se evaluarán los costos de reparación y se decidirá sobre las mejoras necesarias para la temporada de verano.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lugar' => 'Club House - Área Social',
                'fecha' => '2024-12-05',
                'detalle' => 'Asamblea de fin de año para revisar el cumplimiento del presupuesto, presentar el balance general y planificar las actividades para el próximo año. También se discutirán las nuevas políticas de convivencia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lugar' => 'Salón de Eventos - Segundo Piso',
                'fecha' => '2025-02-28',
                'detalle' => 'Asamblea para la aprobación de obras de modernización del sistema eléctrico y de iluminación LED. Se presentará el cronograma de trabajo y el impacto en las cuotas de mantenimiento.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($juntasAsamblea as $junta) {
            JuntasAsamblea::create($junta);
        }
    }
}