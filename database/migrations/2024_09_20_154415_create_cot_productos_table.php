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
        Schema::connection('mysql')->create('cot_productos', function (Blueprint $table) {
            $table->id('idproductos');  // AUTO_INCREMENT para idproductos
            $table->integer('prod_cve')->nullable()->unique();  // Clave única para prod_cve
            $table->integer('prod_cve_syscom')->nullable();  // Clave Syscom
            $table->integer('prod_cve_tvc')->nullable();  // Clave TVC
            $table->tinyText('prod_nombre');  // Nombre del producto
            $table->unsignedBigInteger('idmarca');  // Llave foránea para idmarca
            $table->string('modelo', 50);  // Modelo del producto
            $table->unsignedBigInteger('idproveedor');  // Llave foránea para idproveedor
            $table->string('prod_medicion', 10);  // Unidad de medida
            $table->decimal('prod_precio_brut', 10, 5);  // Precio bruto
            $table->string('prod_tipo', 20);  // Tipo de producto
            $table->string('status', 3)->default('AC');  // Estatus con valor por defecto 'AC'

            // Llaves foráneas
            $table->foreign('idmarca')->references('idmarca')->on('prod_marca')->onDelete('cascade');
            $table->foreign('idproveedor')->references('idproveedor')->on('prod_proveedor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cot_productos');
    }
};
