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
        Schema::create('ticket_links', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ticket_id')->index();
            $table->integer('linked_ticket_id')->index();
            $table->integer('empresa_id')->default(1)->index();
            $table->dateTime('created')->nullable()->useCurrent();

            $table->unique(['ticket_id', 'linked_ticket_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_links');
    }
};
