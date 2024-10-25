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
        Schema::connection('mysql')->create('cot_detalle', function (Blueprint $table) {
            $table->id('idcot_detalle');  // AUTO_INCREMENT para idcot_detalle
            $table->unsignedBigInteger('idcotizacion');  // idcotizacion como clave foránea
            $table->unsignedBigInteger('cotdet_id_producto');  // id del producto como clave foránea
            $table->decimal('cotdet_precio_brut', 10, 5)->nullable();  // precio bruto con hasta 5 decimales, nullable
            $table->string('cotdet_cantidad', 45)->nullable();  // varchar(45) para la cantidad
            $table->decimal('cotdet_precio_desperdicio', 10, 5)->nullable();  // precio desperdicio, nullable
            $table->decimal('cotdet_precio_adicionales', 10, 5)->nullable();  // precio adicionales, nullable
            $table->decimal('cotdet_utilidad', 10, 2);  // utilidad con hasta 2 decimales
            $table->decimal('cotdet_tipo_cambio', 10, 4)->nullable();  // tipo de cambio con hasta 4 decimales, nullable
            $table->string('cotdet_tipo_cot', 2)->nullable();  // tipo de cotización varchar(2), nullable
            $table->text('cotdet_descripcion')->nullable();  // descripción como texto largo, nullable
            $table->decimal('cotdet_subtotal', 10, 5);  // subtotal con hasta 5 decimales
            $table->string('cotdet_moneda', 5);  // moneda varchar(5)
            $table->integer('cotdet_iva');  // clave foránea para el IVA
            $table->string('cotdet_status', 3)->default('AC');  // estado con valor por defecto 'AC'

            // Relaciones
            $table->foreign('idcotizacion')->references('idcotizacion')->on('cot_encabezado');
            $table->foreign('cotdet_id_producto')->references('idproductos')->on('cot_productos');
            // $table->foreign('cotdet_iva')->references('idcotimpuestos')->on('cot_impuestos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cot_detalle');
    }
};
