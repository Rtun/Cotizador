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
        Schema::connection('mysql')->create('cot_adicionales', function (Blueprint $table) {
            $table->id('idcotadicionales');  // Crea el campo idcotadicionales con AUTO_INCREMENT
            $table->string('cotad_nombre', 45);  // varchar(45) para el nombre
            $table->decimal('cotad_precio', 10, 5);  // Campo decimal(10,5) para el precio
            $table->dateTime('fecha_creacion');  // Campo datetime para fecha de creación
            $table->dateTime('fecha_modificacion');  // Campo datetime para fecha de modificación
            $table->string('status', 3)->default('AC');  // varchar(3) para el estado con valor por defecto 'AC'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cot_adicionales');
    }
};
