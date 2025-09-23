<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuntasAsambleasTable extends Migration
{
    public function up()
    {
        Schema::create('juntas_asambleas', function (Blueprint $table) {
            $table->id();
            $table->string('lugar');
            $table->date('fecha');
            $table->text('detalle')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Agregar soporte para soft deletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('juntas_asambleas');
    }
}
