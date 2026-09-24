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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_email_logs_empresa');
            $table->bigInteger('queue_id')->nullable()->index('idx_email_logs_queue');
            $table->string('recipient_email')->nullable();
            $table->string('status', 20)->index('idx_email_logs_status');
            $table->text('error_message')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
