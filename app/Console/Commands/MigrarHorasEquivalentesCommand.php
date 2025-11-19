<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Horas_Mensuales;
use App\Models\ConfiguracionHoras;

class MigrarHorasEquivalentesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horas:migrar-equivalentes {--valor-hora=1000 : Valor por hora a usar para registros existentes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra registros existentes de horas mensuales calculando horas equivalentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $valorHora = $this->option('valor-hora');
        
        $this->info("Iniciando migración de horas equivalentes...");
        $this->info("Valor por hora a usar: $" . number_format($valorHora, 2));
        
        // Confirmar si no existe configuración activa
        $configActual = ConfiguracionHoras::getConfiguracionActual();
        if (!$configActual) {
            $this->warn("No hay configuración activa. Creando configuración inicial...");
            ConfiguracionHoras::create([
                'valor_por_hora' => $valorHora,
                'activo' => true,
                'observaciones' => 'Configuración inicial para migración de datos existentes'
            ]);
        } else {
            $valorHora = $configActual->valor_por_hora;
            $this->info("Usando configuración activa: $" . number_format($valorHora, 2));
        }
        
        if ($valorHora <= 0) {
            $this->error("El valor por hora debe ser mayor a 0");
            return 1;
        }

        // Buscar registros sin horas equivalentes calculadas
        $registrosPendientes = Horas_Mensuales::whereNull('horas_equivalentes_calculadas')->get();
        
        $this->info("Encontrados {$registrosPendientes->count()} registros para procesar");
        
        if ($registrosPendientes->count() === 0) {
            $this->info("No hay registros pendientes de migrar");
            return 0;
        }
        
        if (!$this->confirm("¿Continuar con la migración?")) {
            $this->info("Operación cancelada");
            return 0;
        }
        
        $bar = $this->output->createProgressBar($registrosPendientes->count());
        $bar->start();
        
        $contadorProcesados = 0;
        $contadorConJustificacion = 0;
        
        foreach ($registrosPendientes as $registro) {
            $horasReales = $registro->Cantidad_Horas ?? 0;
            
            if ($registro->Monto_Compensario && $registro->Monto_Compensario > 0) {
                // Registro con justificación
                $registro->valor_hora_al_momento = $valorHora;
                $horasJustificadas = $registro->Monto_Compensario / $valorHora;
                $registro->horas_equivalentes_calculadas = $horasReales + $horasJustificadas;
                $contadorConJustificacion++;
            } else {
                // Registro solo con horas reales
                $registro->horas_equivalentes_calculadas = $horasReales;
            }
            
            $registro->save();
            $contadorProcesados++;
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        
        $this->info("✅ Migración completada:");
        $this->info("  • Registros procesados: {$contadorProcesados}");
        $this->info("  • Con justificación (monto): {$contadorConJustificacion}");
        $this->info("  • Solo horas reales: " . ($contadorProcesados - $contadorConJustificacion));
        $this->info("  • Valor usado: $" . number_format($valorHora, 2) . "/hora");
        
        return 0;
    }
}
