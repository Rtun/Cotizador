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
        Schema::connection('mysql')->create('api_tokens', function (Blueprint $table) {
            $table->id('idtoken');  // Esto crea el campo idtoken con AUTO_INCREMENT
            $table->string('key', 50);  // varchar(50)
            $table->text('token');  // Campo text
            $table->timestamp('expiration');  // timestamp
            // $table->timestamps();  // Agrega los campos created_at y updated_at automáticamente (opcional)

            $table->comment('Almacena el token de las apis que se integran en el sistema');  // Comentario de la tab
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_tokens');
    }
};
