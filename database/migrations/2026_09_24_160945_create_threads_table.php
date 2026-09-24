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
        Schema::create('threads', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ticket_id')->unique('unique_ticket')->comment('Ticket asociado');
            $table->integer('empresa_id')->default(1)->index('idx_threads_empresa_id');
            $table->dateTime('created')->nullable()->useCurrent()->index('idx_created')->comment('Fecha creación');

            $table->index(['empresa_id', 'created'], 'idx_threads_empresa_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};
