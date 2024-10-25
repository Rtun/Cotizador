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
        Schema::connection('mysql')->create('cot_clientes', function (Blueprint $table) {
            $table->id('idclientes');  // Campo AUTO_INCREMENT para idclientes
            $table->string('cli_nombre', 50);  // varchar(50) para el nombre
            $table->string('cli_telefono', 25);  // varchar(10) para el teléfono
            $table->string('cli_correo', 100);  // varchar(50) para el correo electrónico
            $table->string('cli_empresa', 100);  // varchar(45) para el nombre de la empresa
            $table->string('cli_puesto', 45)->nullable();  // varchar(45) para el puesto del cliente
            $table->string('accountname', 50);  // varchar(50) para accountname
            $table->string('status', 3)->default('AC');  // varchar(3) para el estado con valor por defecto 'AC'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cot_clientes');
    }
};


