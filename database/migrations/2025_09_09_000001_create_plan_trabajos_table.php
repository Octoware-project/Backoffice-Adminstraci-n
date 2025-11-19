<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('plan_trabajos', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('unidad_habitacional_id')->nullable();
			$table->integer('mes');
			$table->integer('anio');
			$table->integer('horas_requeridas');
			$table->timestamps();
			$table->softDeletes(); // Agregar soporte para soft deletes
			
			// Índices para optimizar consultas frecuentes
			$table->index(['user_id', 'mes', 'anio'], 'idx_user_mes_anio');
			$table->index('user_id', 'idx_user_id');
			
			// Comentamos la foreign key ya que no tenemos tabla users en esta API
			// $table->foreign('user_id')->references('id')->on('users');
			$table->foreign('unidad_habitacional_id')->references('id')->on('unidades_habitacionales')->onDelete('set null');
			$table->unique(['user_id', 'mes', 'anio']);
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('plan_trabajos');
	}
};
