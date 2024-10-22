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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email',191)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telefono', 10);
            $table->string('empresa', 45)->default('COMSITEC SA DE CV');
            $table->string('web', 30)->default('https://comsitec.com.mx');
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('idrol');
            $table->string('status', 3)->default('AC');

            $table->foreign('idrol')->references('idrol')->on('rol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
