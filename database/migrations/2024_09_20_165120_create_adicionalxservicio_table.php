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
        Schema::create('adicionalxservicio', function (Blueprint $table) {
            $table->id('id_adixserv');  // Esto crea el campo id_adixserv con AUTO_INCREMENT
            $table->unsignedBigInteger('idcotizacion');
            $table->unsignedBigInteger('id_cot_detalle');
            $table->unsignedBigInteger('idadicional');
            $table->integer('cantidad');
            $table->decimal('precio_bruto', 10, 5);
            $table->decimal('subtotal', 10, 5);
            $table->decimal('total', 10, 5);
            $table->string('status', 3)->default('AC');

            // Llaves foráneas
            $table->foreign('idadicional')->references('idcotadicionales')->on('cot_adicionales');
            $table->foreign('idcotizacion')->references('idcotizacion')->on('cot_encabezado');
            $table->foreign('id_cot_detalle')->references('idcot_detalle')->on('cot_detalle');

            // Índices
            $table->index('idcotizacion', 'cotizacion_idx');
            $table->index('idadicional', 'adicional_idx');
            $table->index('id_cot_detalle', 'detalle_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adicionalxservicio');
    }
};
