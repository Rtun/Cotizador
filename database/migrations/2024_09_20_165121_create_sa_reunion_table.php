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
        Schema::connection('mysql')->create('sa_reunion', function (Blueprint $table) {
            $table->id('idreunion');
            $table->unsignedBigInteger('idsala');
            $table->unsignedBigInteger('idusuario');
            $table->string('sare_tema', 50);
            $table->text('sare_descripcion')->nullable();
            $table->dateTime('sare_fecha_inicio');
            $table->dateTime('sare_fecha_fin');
            $table->string('sare_color_fondo', 20);
            $table->string('sare_color_borde', 20);
            $table->string('sare_color_texto', 20);
            $table->string('sare_status', 3)->default('AC');

            $table->foreign('idsala')->references('idsala')->on('salas');
            $table->foreign('idusuario')->references('id')->on('users');

            $table->index('idusuario');
            $table->index('idsala');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sa_reunion');
    }
};
