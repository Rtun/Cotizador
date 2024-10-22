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
        Schema::connection('mysql')->create('prod_proveedor', function (Blueprint $table) {
            $table->id('idproveedor'); // Campo auto-incremental para idproveedor
            $table->string('prv_nombre', 50); // Nombre del proveedor
            $table->unsignedBigInteger('idusuario'); // Relación con el usuario que crea el proveedor
            $table->dateTime('prv_fecha_creacion'); // Fecha de creación del proveedor
            $table->string('prv_rfc', 20)->nullable(); // RFC del proveedor
            $table->string('prv_razon_social', 50)->nullable(); // Razón social del proveedor
            $table->string('prv_direccion', 50)->nullable(); // Dirección del proveedor
            $table->integer('prv_telefono')->nullable(); // Teléfono del proveedor
            $table->string('prv_ciudad', 20)->nullable(); // Ciudad del proveedor
            $table->string('prv_estado', 20)->nullable(); // Estado del proveedor
            $table->string('prv_pais', 20)->nullable(); // País del proveedor
            $table->string('prv_contacto', 20)->nullable(); // Contacto del proveedor
            $table->string('prv_email', 50)->nullable(); // Email del proveedor
            $table->string('prv_status', 3)->default('AC'); // Estado del proveedor con valor por defecto 'AC'

            // Claves foráneas y relaciones
            $table->foreign('idusuario')->references('id')->on('users'); // Relación con la tabla `users`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_proveedor');
    }
};
