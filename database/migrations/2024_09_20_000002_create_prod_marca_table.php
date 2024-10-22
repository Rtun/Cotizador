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
        Schema::connection('mysql')->create('prod_marca', function (Blueprint $table) {
            $table->id('idmarca'); // Crea un campo id marca auto-incremental
            $table->string('m_nombre', 50); // Crea un campo para el nombre de la marca
            $table->unsignedBigInteger('idusuario'); // Crea un campo para el id del usuario
            $table->dateTime('m_fecha_creacion'); // Crea un campo para la fecha de creación
            $table->string('m_status', 3)->default('AC'); // Crea un campo para el estado con valor por defecto 'AC'


            $table->foreign('idusuario')->references('id')->on('users'); // Define la clave foránea
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_marca');
    }
};
