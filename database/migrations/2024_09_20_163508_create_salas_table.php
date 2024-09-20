<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql')->create('salas', function (Blueprint $table) {
            $table->id('idsala');
            $table->string('sa_nombre', 50);
            $table->string('sa_status', 3)->default('AC');

        });


        DB::table('salas')->insert([
            ['idsala' =>1, 'sa_nombre' => 'Oficina de sergio', 'sa_status' => 'AC']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salas');
    }
};
