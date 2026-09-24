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
        Schema::create('thread_entries', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('thread_id')->index()->comment('Conversación');
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('user_id')->nullable()->index()->comment('Usuario que escribió (NULL si es agente)');
            $table->integer('staff_id')->nullable()->index()->comment('Agente que escribió (NULL si es usuario)');
            $table->longText('body')->comment('Contenido del mensaje');
            $table->tinyInteger('is_internal')->nullable()->default(0)->index()->comment('1=nota interna (solo agentes)');
            $table->dateTime('created')->nullable()->useCurrent()->index()->comment('Fecha creación');
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent()->comment('Última edición');

            $table->index(['empresa_id', 'created']);
            $table->index(['thread_id', 'created']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_entries');
    }
};
