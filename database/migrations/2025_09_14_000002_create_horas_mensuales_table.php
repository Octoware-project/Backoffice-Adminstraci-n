<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo crear la tabla horas_mensuales en SQLite (testing)
        if (config('database.default') !== 'sqlite') {
            return;
        }
        Schema::create('horas_mensuales', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->integer('anio');
            $table->integer('mes');
            $table->integer('Cantidad_Horas');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horas_mensuales');
    }
};
