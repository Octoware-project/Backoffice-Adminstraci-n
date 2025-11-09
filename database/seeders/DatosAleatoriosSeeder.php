<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Persona;
use App\Models\UnidadHabitacional;
use App\Models\Factura;
use App\Models\Horas_Mensuales;
use App\Models\PlanTrabajo;
use App\Models\JuntasAsamblea;
use Carbon\Carbon;

class DatosAleatoriosSeeder extends Seeder
{

    public function run(): void
    {

        $fechaInicio = Carbon::create(2025, 7, 1);
        $fechaFin = Carbon::create(2025, 11, 4); 

        $unidades = [];
        for ($i = 1; $i <= 20; $i++) {
            $piso = fake()->numberBetween(1, 15);
            $letra = chr(64 + fake()->numberBetween(1, 8)); // A-H
            $numero = $letra . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            
            $unidades[] = UnidadHabitacional::create([
                'numero_departamento' => $numero,
                'piso' => $piso,
                'estado' => fake()->randomElement(['Finalizado', 'Finalizado', 'Finalizado', 'En construccion']), // 75% finalizado
                'created_at' => fake()->dateTimeBetween($fechaInicio->copy()->subDays(60), $fechaInicio),
                'updated_at' => now(),
            ]);
        }

        $users = [];
        $personas = [];
        
        $nacionalidades = ['Uruguay', 'Argentina', 'Brasil', 'Paraguay', 'Chile', 'Colombia', 'Venezuela', 'Perú'];
        $ocupaciones = ['Ingeniero', 'Médico', 'Profesor', 'Contador', 'Arquitecto', 'Abogado', 'Comerciante', 'Programador', 'Diseñador', 'Enfermero'];
        $estadosCiviles = ['Soltero', 'Casado', 'Divorciado', 'Viudo', 'Unión libre'];
        $generos = ['Masculino', 'Femenino', 'Otro'];

        for ($i = 0; $i < 20; $i++) {
            $fechaAceptacionUsuario = fake()->dateTimeBetween($fechaInicio, $fechaFin);
            
            $email = fake()->unique()->safeEmail();
            $user = User::create([
                'name' => fake()->firstName(),
                'email' => $email,
                'password' => bcrypt('password123'),
                'email_verified_at' => Carbon::parse($fechaAceptacionUsuario)->addDays(rand(1, 10)),
                'created_at' => Carbon::parse($fechaAceptacionUsuario)->subDays(rand(1, 5)),
                'updated_at' => now(),
            ]);
            $users[] = $user;

            $unidadId = (rand(1, 100) <= 80 && count($unidades) > 0) 
                ? $unidades[array_rand($unidades)]->id 
                : null;

            $fechaAsignacion = $unidadId 
                ? Carbon::parse($fechaAceptacionUsuario)->addDays(rand(15, 90)) 
                : null;

            $persona = Persona::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'apellido' => fake()->lastName(),
                'CI' => fake()->unique()->numerify('########'),
                'telefono' => fake()->numerify('09########'),
                'direccion' => fake()->streetAddress() . ', ' . fake()->city(),
                'estadoCivil' => $estadosCiviles[array_rand($estadosCiviles)],
                'genero' => $generos[array_rand($generos)],
                'fechaNacimiento' => fake()->dateTimeBetween('-65 years', '-25 years')->format('Y-m-d'),
                'ocupacion' => $ocupaciones[array_rand($ocupaciones)],
                'nacionalidad' => $nacionalidades[array_rand($nacionalidades)],
                'estadoRegistro' => 'Aceptado',
                'fecha_aceptacion' => $fechaAceptacionUsuario,
                'unidad_habitacional_id' => $unidadId,
                'fecha_asignacion_unidad' => $fechaAsignacion,
                'created_at' => Carbon::parse($fechaAceptacionUsuario)->subDays(rand(1, 5)),
                'updated_at' => now(),
            ]);
            $personas[] = $persona;
        }

        $tiposPago = ['Transferencia', 'Efectivo', 'Cheque', 'Tarjeta de crédito', 'Tarjeta de débito'];
        // Mayoría aceptadas: 80% Aceptado, 15% Pendiente, 5% Rechazado
        $estadosPago = ['Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Aceptado', 'Pendiente', 'Pendiente', 'Pendiente', 'Rechazado'];

        $motivosFactura = [
            'Cuota mensual de mantenimiento',
            'Gastos comunes del mes',
            'Reparación de áreas comunes',
            'Mantenimiento de ascensores',
            'Limpieza y jardinería',
            'Seguro del edificio',
            'Servicios públicos compartidos',
            'Fondo de reserva',
        ];

        for ($i = 0; $i < 20; $i++) {
            $user = $users[array_rand($users)];
            
            $mesAleatorio = rand(7, 11); // Julio a Noviembre
            $diaAleatorio = rand(1, 28);
            $fechaCreacion = Carbon::create(2025, $mesAleatorio, $diaAleatorio, rand(8, 20), rand(0, 59));
            
            $estadoPago = $estadosPago[array_rand($estadosPago)];
            
            // Si está aceptado, tiene fecha de pago
            $fechaPago = null;
            if ($estadoPago === 'Aceptado') {
                $fechaPago = $fechaCreacion->copy()->addDays(rand(1, 15))->format('Y-m-d');
            }
            
            Factura::create([
                'email' => $user->email,
                'Monto' => fake()->randomFloat(2, 500, 8000),
                'Archivo_Comprobante' => rand(1, 100) <= 85 ? 'comprobante_' . fake()->uuid() . '.pdf' : null,
                'Estado_Pago' => $estadoPago,
                'tipo_pago' => $tiposPago[array_rand($tiposPago)],
                'fecha_pago' => $fechaPago,
                'motivo' => $motivosFactura[array_rand($motivosFactura)],
                'created_at' => $fechaCreacion,
                'updated_at' => now(),
            ]);
        }

        $motivosFalla = [
            'Enfermedad', 
            'Emergencia familiar', 
            'Viaje', 
            'Trabajo', 
            'Estudios',
            'Problemas de salud',
            'Permiso personal',
            null, null, null
        ];
        
        $tiposJustificacion = [
            'Certificado médico',
            'Comprobante',
            'Documento oficial',
            'Declaración jurada',
            null, null 
        ];

        $mesesPasados = [];
        for ($i = 0; $i < 5; $i++) {
            $fecha = Carbon::now()->subMonths($i);
            $mesesPasados[] = ['mes' => $fecha->month, 'anio' => $fecha->year];
        }

        for ($i = 0; $i < 20; $i++) {
            $user = $users[array_rand($users)];
            $periodo = $mesesPasados[array_rand($mesesPasados)];
            
            $cantidadHoras = rand(0, 24);
            $motivoFalla = $cantidadHoras < 15 ? $motivosFalla[array_rand($motivosFalla)] : null;
            $tipoJust = $motivoFalla ? $tiposJustificacion[array_rand($tiposJustificacion)] : null;
            $montoComp = ($cantidadHoras < 15 && $motivoFalla) ? fake()->randomFloat(2, 100, 2000) : 0;
            $valorHora = fake()->randomFloat(2, 150, 300);
            $horasJustificadas = $montoComp > 0 ? round($montoComp / $valorHora, 2) : 0;

            Horas_Mensuales::create([
                'email' => $user->email,
                'anio' => $periodo['anio'],
                'mes' => $periodo['mes'],
                'dia' => rand(1, 28),
                'Cantidad_Horas' => $cantidadHoras,
                'Motivo_Falla' => $motivoFalla,
                'Tipo_Justificacion' => $tipoJust,
                'Monto_Compensario' => $montoComp,
                'valor_hora_al_momento' => $valorHora,
                'horas_equivalentes_calculadas' => $cantidadHoras + $horasJustificadas,
                'created_at' => Carbon::create($periodo['anio'], $periodo['mes'], rand(1, 28)),
                'updated_at' => now(),
            ]);
        }

        
        $planesCreados = [];
        $intentos = 0;
        $planesGenerados = 0;
        
        while ($planesGenerados < 20 && $intentos < 100) {
            $user = $users[array_rand($users)];
            $periodo = $mesesPasados[array_rand($mesesPasados)];
            
            $clave = $user->id . '-' . $periodo['mes'] . '-' . $periodo['anio'];
            
            if (in_array($clave, $planesCreados)) {
                $intentos++;
                continue;
            }
            
            $personaUser = $personas[array_search($user, $users)];
            $unidadId = $personaUser->unidad_habitacional_id ?? $unidades[array_rand($unidades)]->id;

            PlanTrabajo::create([
                'user_id' => $user->id,
                'unidad_habitacional_id' => $unidadId,
                'mes' => $periodo['mes'],
                'anio' => $periodo['anio'],
                'horas_requeridas' => fake()->numberBetween(16, 32),
                'created_at' => Carbon::create($periodo['anio'], $periodo['mes'], rand(1, 10)),
                'updated_at' => now(),
            ]);
            
            $planesCreados[] = $clave;
            $planesGenerados++;
            $intentos++;
        }

        
        $lugares = [
            'Salón de eventos del edificio',
            'Sala de reuniones - Piso 1',
            'Terraza común',
            'Quincho del complejo',
            'Auditorio principal',
            'Sala multiuso',
            'Patio central',
            'Barbacoa común',
        ];

        $temas = [
            'Asamblea ordinaria - Revisión de gastos comunes',
            'Reunión extraordinaria - Aprobación de obras',
            'Junta anual - Balance del ejercicio',
            'Asamblea especial - Elección de nueva comisión',
            'Reunión de consorcio - Mejoras edilicias',
            'Asamblea general - Presupuesto anual',
            'Junta informativa - Nuevos proyectos',
            'Reunión ordinaria - Revisión de reglamento',
            'Asamblea urgente - Reparaciones necesarias',
            'Junta trimestral - Seguimiento de obras',
        ];

        for ($i = 0; $i < 20; $i++) {
            $fechaJunta = fake()->dateTimeBetween($fechaInicio, 'now');
            
            JuntasAsamblea::create([
                'lugar' => $lugares[array_rand($lugares)],
                'fecha' => $fechaJunta,
                'detalle' => $temas[array_rand($temas)] . '. ' . fake()->sentence(rand(10, 20)),
                'created_at' => $fechaJunta,
                'updated_at' => now(),
            ]);
        }
    }
}
