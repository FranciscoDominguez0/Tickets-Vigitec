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
            $table->integer('ticket_id')->unique()->comment('Ticket asociado');
            $table->integer('empresa_id')->default(1)->index();
            $table->dateTime('created')->nullable()->useCurrent()->index()->comment('Fecha creación');

            $table->index(['empresa_id', 'created']);
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
