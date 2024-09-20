<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql')->create('tipo_cambio', function (Blueprint $table) {
            $table->id('idtipocambio');
            $table->decimal('valor', 10, 3);

            $table->comment('Esta tabla es únicamente para el almacenamiento del tipo de cambio, no está ligada a ninguna otra.');
        });

        DB::table('tipo_cambio')->insert([
            ['idtipocambio' => 1, 'valor' => 19.877]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_cambio');
    }
};
