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
        Schema::create('quote_messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('quote_id')->index('quote_id');
            $table->integer('user_id')->nullable();
            $table->integer('staff_id')->nullable();
            $table->text('message');
            $table->string('file_path')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_messages');
    }
};
