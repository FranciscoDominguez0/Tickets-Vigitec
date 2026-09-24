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
            $table->integer('thread_id')->index('idx_thread_id')->comment('Conversación');
            $table->integer('empresa_id')->default(1)->index('idx_thread_entries_empresa_id');
            $table->integer('user_id')->nullable()->index('idx_user_id')->comment('Usuario que escribió (NULL si es agente)');
            $table->integer('staff_id')->nullable()->index('idx_staff_id')->comment('Agente que escribió (NULL si es usuario)');
            $table->longText('body')->comment('Contenido del mensaje');
            $table->tinyInteger('is_internal')->nullable()->default(0)->index('idx_internal')->comment('1=nota interna (solo agentes)');
            $table->dateTime('created')->nullable()->useCurrent()->index('idx_created')->comment('Fecha creación');
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent()->comment('Última edición');

            $table->index(['empresa_id', 'created'], 'idx_thread_entries_empresa_created');
            $table->index(['thread_id', 'created'], 'idx_thread_entries_thread');
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
