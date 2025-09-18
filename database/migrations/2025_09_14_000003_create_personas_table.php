<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo crear la tabla personas en SQLite (testing)
        if (config('database.default') !== 'sqlite') {
            return;
        }
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('apellido')->nullable();
            $table->string('CI')->nullable();
            $table->string('Telefono')->nullable();
            $table->string('Direccion')->nullable();
            $table->string('estadoRegistro')->default('Pendiente');
            $table->string('Estado_Registro')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
