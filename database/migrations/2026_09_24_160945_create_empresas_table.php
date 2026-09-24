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
        Schema::create('empresas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombre')->unique();
            $table->enum('estado', ['activa', 'suspendida', 'bloqueada'])->default('activa')->index();
            $table->dateTime('fecha_creacion')->useCurrent();
            $table->decimal('precio_mensual', 10)->default(0);
            $table->date('fecha_inicio_servicio')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->integer('dias_gracia')->default(0);
            $table->enum('estado_pago', ['al_dia', 'vencido', 'suspendido'])->default('al_dia');
            $table->boolean('bloqueada')->default(false);
            $table->string('motivo_bloqueo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
