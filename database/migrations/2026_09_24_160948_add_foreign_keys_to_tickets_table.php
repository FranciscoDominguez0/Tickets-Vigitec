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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreign(['topic_id'], 'fk_tickets_topic')->references(['id'])->on('help_topics')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['user_id'], 'tickets_ibfk_1')->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['staff_id'], 'tickets_ibfk_2')->references(['id'])->on('staff')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['dept_id'], 'tickets_ibfk_3')->references(['id'])->on('departments')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['status_id'], 'tickets_ibfk_4')->references(['id'])->on('ticket_status')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['priority_id'], 'tickets_ibfk_5')->references(['id'])->on('priorities')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign('fk_tickets_topic');
            $table->dropForeign('tickets_ibfk_1');
            $table->dropForeign('tickets_ibfk_2');
            $table->dropForeign('tickets_ibfk_3');
            $table->dropForeign('tickets_ibfk_4');
            $table->dropForeign('tickets_ibfk_5');
        });
    }
};
