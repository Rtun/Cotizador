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
        Schema::connection('mysql')->create('cot_impuestos', function (Blueprint $table) {
            $table->id('idcotimpuestos');  // AUTO_INCREMENT para idcotimpuestos
            $table->string('nombre', 45);  // Nombre del impuesto
            $table->float('valor');  // Valor del impuesto
            $table->string('status', 3)->default('AC');  // Estatus con valor por defecto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cot_impuestos');
    }
};
