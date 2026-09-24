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
        Schema::create('ticket_status', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 50)->index()->comment('Nombre del estado');
            $table->string('color', 20)->nullable()->comment('Color en hex (ej: #3498db)');
            $table->string('icon', 50)->nullable()->comment('Ícono Font Awesome');
            $table->integer('order_by')->nullable()->default(0)->comment('Orden de visualización');

            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_status');
    }
};
