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
        Schema::create('staff_ticket_seen', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('staff_id');
            $table->integer('ticket_id')->index('idx_ticket');
            $table->dateTime('seen_at')->useCurrent();

            $table->index(['staff_id', 'seen_at'], 'idx_staff_seen_at');
            $table->unique(['staff_id', 'ticket_id'], 'uq_staff_ticket_seen_original_pk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_ticket_seen');
    }
};
