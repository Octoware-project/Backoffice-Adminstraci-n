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
        Schema::create('unidades_habitacionales', function (Blueprint $table) {
            $table->id();
            $table->string('numero_departamento');
            $table->integer('piso');
            $table->enum('estado', ['En construccion', 'Finalizado'])->default('En construccion');
            $table->timestamps();
            $table->softDeletes();
            
            // Índices únicos
            $table->unique('numero_departamento', 'unidades_habitacionales_numero_departamento_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_habitacionales');
    }
};
