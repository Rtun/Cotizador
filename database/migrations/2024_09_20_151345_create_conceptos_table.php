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
        Schema::connection('mysql')->create('conceptos', function (Blueprint $table) {
            $table->id('idconcepto');  // Crea el campo idconcepto con AUTO_INCREMENT
            $table->string('con_clave', 20);  // varchar(20) con utf8mb3
            $table->text('con_texto');  // Campo text con utf8mb3
            $table->string('con_status', 2)->default('AC');  // varchar(2) con valor por defecto 'AC'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conceptos');
    }
};
