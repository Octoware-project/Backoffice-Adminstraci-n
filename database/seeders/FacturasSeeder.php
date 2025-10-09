<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Factura;
use App\Models\User;
use App\Models\Persona;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FacturasSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los usuarios aceptados (tienen personas con estadoRegistro 'Aceptado')
        $usuariosAceptados = User::whereHas('persona', function($query) {
            $query->where('estadoRegistro', 'Aceptado');
        })->with('persona')->get();

        if ($usuariosAceptados->isEmpty()) {
            return;
        }

        // Comprobantes disponibles (ubicados en storage/app/public/comprobantes)
        $comprobantes = [
            'comprobantes/comprobante1.pdf',
            'comprobantes/comprobante2.pdf', 
            'comprobantes/comprobante3.pdf'
        ];

        // Generar fechas de los últimos 6 meses
        $fechasUltimos6Meses = $this->generarFechasUltimos6Meses();
        
        // Estados posibles para las facturas
        $estadosPago = ['Pendiente', 'Aceptado', 'Rechazado'];
        
        // Tipos de pago posibles
        $tiposPago = ['Transferencia', 'Efectivo', 'Cheque', 'Tarjeta'];

        // Crear 56 facturas
        for ($i = 1; $i <= 56; $i++) {
            // Seleccionar usuario aceptado aleatorio
            $usuario = $usuariosAceptados->random();
            
            // Generar monto aleatorio entre $500 y $5000
            $monto = rand(500, 5000);
            
            // Seleccionar fecha aleatoria de los últimos 6 meses
            $fechaFactura = $fechasUltimos6Meses[array_rand($fechasUltimos6Meses)];
            
            // Seleccionar comprobante aleatorio
            $comprobante = $comprobantes[array_rand($comprobantes)];
            
            // Determinar estado de pago (70% Aceptado, 20% Pendiente, 10% Rechazado)
            $rand = rand(1, 100);
            if ($rand <= 70) {
                $estadoPago = 'Aceptado';
            } elseif ($rand <= 90) {
                $estadoPago = 'Pendiente';
            } else {
                $estadoPago = 'Rechazado';
            }
            
            // Crear la factura
            Factura::create([
                'email' => $usuario->email,
                'Monto' => $monto,
                'Archivo_Comprobante' => $comprobante,
                'Estado_Pago' => $estadoPago,
                'tipo_pago' => $tiposPago[array_rand($tiposPago)],
                'fecha_pago' => $fechaFactura->format('Y-m-d'),
                'created_at' => $fechaFactura,
                'updated_at' => $fechaFactura,
            ]);
        }
    }

    /**
     * Genera fechas aleatorias de los últimos 6 meses
     */
    private function generarFechasUltimos6Meses(): array
    {
        $fechas = [];
        $fechaActual = Carbon::now();
        
        // Generar fechas para cada mes de los últimos 6 meses
        for ($i = 0; $i < 6; $i++) {
            $mesAnterior = $fechaActual->copy()->subMonths($i);
            $inicioMes = $mesAnterior->copy()->startOfMonth();
            $finMes = $mesAnterior->copy()->endOfMonth();
            
            // Generar varias fechas aleatorias para cada mes
            for ($j = 0; $j < 15; $j++) {
                $fechaAleatoria = $inicioMes->copy()->addDays(rand(0, $inicioMes->diffInDays($finMes)));
                $fechas[] = $fechaAleatoria;
            }
        }
        
        return $fechas;
    }
}