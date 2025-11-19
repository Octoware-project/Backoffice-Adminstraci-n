<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('horas_mensuales', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->integer('anio');
            $table->integer('mes');
            $table->integer('dia');
            $table->integer('Cantidad_Horas')->nullable();
            $table->string('Motivo_Falla')->nullable();
            $table->string('Tipo_Justificacion')->nullable();
            $table->float('Monto_Compensario', 10, 2)->nullable();
        // Valor por hora que se usó al momento de crear este registro
        $table->decimal('valor_hora_al_momento', 10, 2)->nullable()
            ->comment('Valor por hora usado para calcular horas equivalentes');
        // Horas equivalentes ya calculadas (para no recalcular)
        $table->decimal('horas_equivalentes_calculadas', 8, 2)->nullable()
            ->comment('Total de horas reales + horas de justificación calculadas');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para optimizar consultas
            $table->index(['email', 'mes', 'anio'], 'idx_email_mes_anio');
            $table->index('email', 'idx_email');
            $table->index('created_at', 'idx_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('horas_mensuales');
    }
};
