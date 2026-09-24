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
        Schema::create('priorities', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50)->unique()->comment('Nombre de prioridad');
            $table->integer('level')->nullable()->default(0)->index()->comment('Nivel numérico (1=bajo, 4=urgente)');
            $table->string('color', 20)->nullable()->comment('Color en hex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('priorities');
    }
};
