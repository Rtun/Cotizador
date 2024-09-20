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
        Schema::connection('mysql')->create('rolxpermiso', function (Blueprint $table) {
            $table->unsignedBigInteger('idrol'); // Referencia al ID del rol
            $table->unsignedBigInteger('idpermiso'); // Referencia al ID del permiso

            $table->foreign('idrol')->references('idrol')->on('rol')->onDelete('cascade'); // Clave foránea
            $table->foreign('idpermiso')->references('idpermiso')->on('permisos')->onDelete('cascade'); // Clave foránea

            // $table->primary(['idrol', 'idpermiso']); // Clave primaria compuesta
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolxpermiso');
    }
};
