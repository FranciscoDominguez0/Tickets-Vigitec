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
        Schema::table('thread_entries', function (Blueprint $table) {
            $table->foreign(['thread_id'], 'thread_entries_ibfk_1')->references(['id'])->on('threads')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'], 'thread_entries_ibfk_2')->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['staff_id'], 'thread_entries_ibfk_3')->references(['id'])->on('staff')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thread_entries', function (Blueprint $table) {
            $table->dropForeign('thread_entries_ibfk_1');
            $table->dropForeign('thread_entries_ibfk_2');
            $table->dropForeign('thread_entries_ibfk_3');
        });
    }
};
