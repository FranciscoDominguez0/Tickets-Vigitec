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
        Schema::create('email_queue', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('empresa_id')->default(1)->index();
            $table->string('recipient_email');
            $table->string('subject');
            $table->mediumText('body_html')->nullable();
            $table->mediumText('body_text')->nullable();
            $table->mediumText('attachments_json')->nullable();
            $table->string('status', 20)->default('pending');
            $table->integer('attempts')->default(0);
            $table->integer('max_attempts')->default(5);
            $table->dateTime('next_attempt_at')->useCurrent();
            $table->text('last_error')->nullable();
            $table->string('context_type', 50)->nullable();
            $table->integer('context_id')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            $table->index(['context_type', 'context_id']);
            $table->index(['status', 'next_attempt_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_queue');
    }
};
