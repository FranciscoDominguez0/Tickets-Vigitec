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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id');
            $table->integer('user_id');
            $table->string('type', 50);
            $table->string('message');
            $table->integer('ticket_id')->nullable()->index();
            $table->integer('thread_entry_id')->nullable()->index();
            $table->boolean('is_read')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('read_at')->nullable();

            $table->index(['empresa_id', 'user_id', 'is_read', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
