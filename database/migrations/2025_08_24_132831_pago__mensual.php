<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('pagos_mensuales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('persona_id')->constrained('personas');
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->string('mes_correspondiente'); 
            $table->string('año_correspondiente')->default(date('Y'));
            $table->string('comprobante')->nullable();

            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
