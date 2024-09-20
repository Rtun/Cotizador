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
        Schema::connection('mysql')->create('cot_utilidad', function (Blueprint $table) {
            $table->id('idutilidad');  // AUTO_INCREMENT para idutilidad
            $table->string('nombre', 50);  // Nombre del concepto de utilidad
            $table->float('valor');  // Valor numérico
            $table->string('status', 3)->default('AC');  // Estatus con valor por defecto 'AC'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cot_utilidad');
    }
};
