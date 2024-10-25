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
        Schema::connection('mysql')->create('sist_bitacora', function (Blueprint $table) {
            $table->id('idbitacora');
            $table->string('tabla_modificada', 150);
            $table->integer('objeto_modificado');
            $table->integer('idusuario'); // Asegúrate de que el tipo de datos coincida con la tabla de usuarios
            $table->dateTime('fecha');
            $table->string('accion', 45);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sist_bitacora');
    }
};
