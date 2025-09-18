<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo crear la tabla plan_trabajos en SQLite (testing)
        if (config('database.default') !== 'sqlite') {
            return;
        }
        Schema::create('plan_trabajos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('mes');
            $table->integer('anio');
            $table->integer('horas_requeridas');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_trabajos');
    }
};
