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
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id', 128)->primary()->comment('ID de sesión');
            $table->string('user_type', 50)->nullable()->index('idx_user_type');
            $table->integer('user_id')->nullable()->index('idx_user_id')->comment('ID del usuario');
            $table->binary('data')->nullable()->comment('Datos de sesión');
            $table->dateTime('created')->nullable()->useCurrent()->comment('Creación');
            $table->dateTime('expires')->nullable()->index('idx_expires')->comment('Expiración');
            $table->dateTime('last_activity')->useCurrentOnUpdate()->nullable()->useCurrent()->comment('Último movimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
