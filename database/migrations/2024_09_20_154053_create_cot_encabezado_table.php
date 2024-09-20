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
        Schema::connection('mysql')->create('cot_encabezado', function (Blueprint $table) {
            $table->id('idcotizacion');  // AUTO_INCREMENT para idcotizacion
            $table->unsignedBigInteger('idusuario');  // idusuario como clave foránea
            $table->unsignedBigInteger('idcliente');  // idcliente como clave foránea
            $table->integer('cot_num_crm');  // número de CRM
            $table->string('cot_encabezado', 50);  // encabezado de cotización
            $table->unsignedBigInteger('cot_concepto');  // id del concepto
            $table->string('estado_cot', 15)->default('En curso');  // estado con valor por defecto 'En curso'
            $table->integer('cot_prod_cantidad');  // cantidad de productos
            $table->dateTime('cot_fecha_creacion');  // fecha de creación
            $table->dateTime('cot_fecha_modificacion');  // fecha de modificación
            $table->dateTime('cot_fecha_cierre')->nullable();  // fecha de cierre, nullable
            $table->string('cot_documento', 100);  // nombre del documento
            $table->string('status', 3)->default('AC');  // estatus con valor por defecto 'AC'

            // Índices y claves foráneas
            $table->foreign('idusuario')->references('id')->on('users');
            $table->foreign('idcliente')->references('idclientes')->on('cot_clientes');
            $table->foreign('cot_concepto')->references('idconcepto')->on('conceptos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cot_encabezado');
    }
};
