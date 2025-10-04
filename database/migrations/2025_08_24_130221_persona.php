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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('unidad_habitacional_id')->nullable();
            $table->timestamp('fecha_asignacion_unidad')->nullable();
            $table->string('name');
            $table->string('apellido');
            $table->string('CI');
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('estadoCivil')->nullable();
            $table->string('genero')->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('estadoRegistro');
            $table->timestamp('fecha_aceptacion')->nullable(); // Fecha cuando el usuario fue aceptado
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('unidad_habitacional_id')->references('id')->on('unidades_habitacionales')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
