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
        Schema::connection('mysql')->create('permisos', function (Blueprint $table) {
            $table->id('idpermiso');  // AUTO_INCREMENT para idpermiso
            $table->string('nombre', 50);  // Nombre del permiso
            $table->string('clave', 15);  // Clave del permiso
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
