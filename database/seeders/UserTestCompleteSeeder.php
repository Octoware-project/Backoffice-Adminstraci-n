<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Horas_Mensuales;
use App\Models\PlanTrabajo;
use App\Models\UnidadHabitacional;
use App\Models\Factura;
use Carbon\Carbon;

class UserTestCompleteSeeder extends Seeder
{
    public function run()
    {
        $email = 'user@test.com';
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return;
        }

        $unidad = UnidadHabitacional::first();
        
        // ==============================================
        // SEPTIEMBRE 2025
        // ==============================================
        
        // Plan de trabajo para Septiembre
        PlanTrabajo::updateOrCreate(
            [
                'user_id' => $user->id,
                'mes' => 9,
                'anio' => 2025
            ],
            [
                'unidad_habitacional_id' => $unidad ? $unidad->id : null,
                'horas_requeridas' => 160, // 160 horas requeridas (20 días x 8 horas)
            ]
        );

        // Horas trabajadas - Semana 1 (días 2-6): 40 horas
        for ($dia = 2; $dia <= 6; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 9,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 9, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 9, $dia, 18, 0, 0),
            ]);
        }

        // Semana 2 (días 9-13): 40 horas
        for ($dia = 9; $dia <= 13; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 9,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 9, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 9, $dia, 18, 0, 0),
            ]);
        }

        // Día 16: Justificación médica (8 horas)
        Horas_Mensuales::create([
            'email' => $email,
            'anio' => 2025,
            'mes' => 9,
            'dia' => 16,
            'Cantidad_Horas' => 0,
            'Motivo_Falla' => 'Consulta médica de rutina',
            'Tipo_Justificacion' => 'Médica',
            'Monto_Compensario' => 8000.00, // 8 horas x $1000
            'created_at' => Carbon::create(2025, 9, 16, 10, 0, 0),
            'updated_at' => Carbon::create(2025, 9, 16, 10, 0, 0),
        ]);

        // Semana 3 (días 17-20): 32 horas
        for ($dia = 17; $dia <= 20; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 9,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 9, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 9, $dia, 18, 0, 0),
            ]);
        }

        // Semana 4 (días 23-27): 40 horas
        for ($dia = 23; $dia <= 27; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 9,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 9, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 9, $dia, 18, 0, 0),
            ]);
        }

        // Total Septiembre: 152 horas trabajadas + 8 horas justificadas = 160 horas (100%)

        // ==============================================
        // OCTUBRE 2025
        // ==============================================
        
        // Plan de trabajo para Octubre
        PlanTrabajo::updateOrCreate(
            [
                'user_id' => $user->id,
                'mes' => 10,
                'anio' => 2025
            ],
            [
                'unidad_habitacional_id' => $unidad ? $unidad->id : null,
                'horas_requeridas' => 176, // 22 días x 8 horas
            ]
        );

        // Semana 1 (días 1-3): 24 horas
        for ($dia = 1; $dia <= 3; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 10,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 10, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 10, $dia, 18, 0, 0),
            ]);
        }

        // Semana 1 cont. (días 6-10): 40 horas
        for ($dia = 6; $dia <= 10; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 10,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 10, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 10, $dia, 18, 0, 0),
            ]);
        }

        // Semana 2 (días 13-17): 40 horas
        for ($dia = 13; $dia <= 17; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 10,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 10, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 10, $dia, 18, 0, 0),
            ]);
        }

        // Día 20: Medio día + justificación personal
        Horas_Mensuales::create([
            'email' => $email,
            'anio' => 2025,
            'mes' => 10,
            'dia' => 20,
            'Cantidad_Horas' => 4,
            'Motivo_Falla' => 'Trámite bancario urgente',
            'Tipo_Justificacion' => 'Personal',
            'Monto_Compensario' => 4000.00, // 4 horas x $1000
            'created_at' => Carbon::create(2025, 10, 20, 9, 0, 0),
            'updated_at' => Carbon::create(2025, 10, 20, 13, 0, 0),
        ]);

        // Semana 3 cont. (días 21-24): 32 horas
        for ($dia = 21; $dia <= 24; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 10,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 10, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 10, $dia, 18, 0, 0),
            ]);
        }

        // Semana 4 (días 27-30): 32 horas
        for ($dia = 27; $dia <= 30; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 10,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 10, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 10, $dia, 18, 0, 0),
            ]);
        }

        // Total Octubre: 172 horas trabajadas + 4 horas justificadas = 176 horas (100%)

        // ==============================================
        // NOVIEMBRE 2025
        // ==============================================
        
        // Plan de trabajo para Noviembre
        PlanTrabajo::updateOrCreate(
            [
                'user_id' => $user->id,
                'mes' => 11,
                'anio' => 2025
            ],
            [
                'unidad_habitacional_id' => $unidad ? $unidad->id : null,
                'horas_requeridas' => 160, // 20 días x 8 horas
            ]
        );

        // Semana 1 (días 3-7): 40 horas - Ya pasó
        for ($dia = 3; $dia <= 7; $dia++) {
            Horas_Mensuales::create([
                'email' => $email,
                'anio' => 2025,
                'mes' => 11,
                'dia' => $dia,
                'Cantidad_Horas' => 8,
                'Motivo_Falla' => null,
                'Tipo_Justificacion' => null,
                'Monto_Compensario' => 0,
                'created_at' => Carbon::create(2025, 11, $dia, 9, 0, 0),
                'updated_at' => Carbon::create(2025, 11, $dia, 18, 0, 0),
            ]);
        }

        // Días 10-14: En progreso (hasta hoy 4 de nov solo día 10 está completo)
        // No agregamos más porque estamos el 4 de noviembre

        // ==============================================
        // FACTURAS / COMPROBANTES
        // ==============================================
        
        // SEPTIEMBRE 2025 - Pago aceptado (mes completo cumplido)
        Factura::create([
            'email' => $email,
            'Monto' => 7500.00,
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Aceptado',
            'tipo_pago' => 'Transferencia',
            'fecha_pago' => '2025-09-30',
            'motivo' => 'Pago mensual - Septiembre 2025',
            'created_at' => Carbon::create(2025, 9, 30, 16, 30, 0),
            'updated_at' => Carbon::create(2025, 10, 2, 10, 15, 0), // Aceptado 2 días después
        ]);

        // OCTUBRE 2025 - Factura pendiente (recién enviada hace 3 días)
        Factura::create([
            'email' => $email,
            'Monto' => 820.00,
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Pendiente',
            'tipo_pago' => 'Transferencia',
            'fecha_pago' => '2025-10-31',
            'motivo' => 'Pago mensual - Octubre 2025',
            'created_at' => Carbon::create(2025, 11, 5, 14, 20, 0), // Hace 3 días
            'updated_at' => Carbon::create(2025, 11, 5, 14, 20, 0),
        ]);

        // AGOSTO 2025 - Pago rechazado (monto incorrecto)
        Factura::create([
            'email' => $email,
            'Monto' => 500.00, // Monto menor al esperado
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Rechazado',
            'tipo_pago' => 'Efectivo',
            'fecha_pago' => '2025-08-31',
            'motivo' => 'Pago mensual - Agosto 2025',
            'created_at' => Carbon::create(2025, 8, 31, 11, 0, 0),
            'updated_at' => Carbon::create(2025, 9, 3, 9, 45, 0), // Rechazado después de revisión
        ]);

        // JULIO 2025 - Pago aceptado (antiguo)
        Factura::create([
            'email' => $email,
            'Monto' => 7000.00,
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Aceptado',
            'tipo_pago' => 'Transferencia',
            'fecha_pago' => '2025-07-31',
            'motivo' => 'Pago mensual - Julio 2025',
            'created_at' => Carbon::create(2025, 7, 31, 18, 0, 0),
            'updated_at' => Carbon::create(2025, 8, 1, 11, 30, 0),
        ]);

        // NOVIEMBRE 2025 - Pago adelantado pendiente (de hace 2 días)
        Factura::create([
            'email' => $email,
            'Monto' => 450.00,
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Pendiente',
            'tipo_pago' => 'Transferencia',
            'fecha_pago' => '2025-11-06',
            'motivo' => 'Adelanto - Primera quincena Noviembre 2025',
            'created_at' => Carbon::create(2025, 11, 6, 10, 15, 0), // Hace 2 días
            'updated_at' => Carbon::create(2025, 11, 6, 10, 15, 0),
        ]);

        // JUNIO 2025 - Pago aceptado con justificación
        Factura::create([
            'email' => $email,
            'Monto' => 680.00,
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Aceptado',
            'tipo_pago' => 'Cheque',
            'fecha_pago' => '2025-06-28',
            'motivo' => 'Pago mensual - Junio 2025 (incluye compensación)',
            'created_at' => Carbon::create(2025, 6, 28, 15, 45, 0),
            'updated_at' => Carbon::create(2025, 6, 30, 14, 0, 0),
        ]);

        // OCTUBRE 2025 - Segundo comprobante rechazado (duplicado/error)
        Factura::create([
            'email' => $email,
            'Monto' => 8250.00,
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Rechazado',
            'tipo_pago' => 'Transferencia',
            'fecha_pago' => '2025-10-31',
            'motivo' => 'Pago mensual - Octubre 2025 (duplicado)',
            'created_at' => Carbon::create(2025, 11, 1, 9, 0, 0),
            'updated_at' => Carbon::create(2025, 11, 2, 10, 30, 0), // Rechazado rápido
        ]);

        // MAYO 2025 - Pago aceptado (antiguo)
        Factura::create([
            'email' => $email,
            'Monto' => 650.00,
            'Archivo_Comprobante' => 'comprobantes/comprobante.pdf',
            'Estado_Pago' => 'Aceptado',
            'tipo_pago' => 'Transferencia',
            'fecha_pago' => '2025-05-31',
            'motivo' => 'Pago mensual - Mayo 2025',
            'created_at' => Carbon::create(2025, 5, 31, 17, 30, 0),
            'updated_at' => Carbon::create(2025, 6, 2, 9, 15, 0),
        ]);
    }
}
