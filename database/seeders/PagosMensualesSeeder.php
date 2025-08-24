<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PagoMensual; // Asegurate de tener el modelo
use Illuminate\Support\Facades\DB;

class PagosMensualesSeeder extends Seeder
{
     public function run(): void
    {
        $pagos = [
            ['persona_id' => 1, 'monto' => 1000.00, 'fecha_pago' => '2025-08-01', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 2, 'monto' => 1050.50, 'fecha_pago' => '2025-08-02', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 3, 'monto' => 1100.75, 'fecha_pago' => '2025-08-03', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 4, 'monto' => 1150.25, 'fecha_pago' => '2025-08-04', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 5, 'monto' => 1200.00, 'fecha_pago' => '2025-08-05', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 6, 'monto' => 1250.50, 'fecha_pago' => '2025-08-06', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 7, 'monto' => 1300.75, 'fecha_pago' => '2025-08-07', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 8, 'monto' => 1350.25, 'fecha_pago' => '2025-08-08', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 9, 'monto' => 1400.00, 'fecha_pago' => '2025-08-09', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
            ['persona_id' => 10, 'monto' => 1450.50, 'fecha_pago' => '2025-08-10', 'mes_correspondiente' => 'Agosto', 'año_correspondiente'=>'2025'],
        ];

        foreach ($pagos as $pago) {
            DB::table('pagos_mensuales')->insert([
                'persona_id' => $pago['persona_id'],
                'monto' => $pago['monto'],
                'fecha_pago' => $pago['fecha_pago'],
                'mes_correspondiente' => $pago['mes_correspondiente'],
                'comprobante' => null,
                'estado' => 'Pendiente',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
