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
        Schema::create('ticket_deletion_requests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ticket_id');
            $table->integer('empresa_id')->index('idx_empresa_id');
            $table->string('ticket_number', 100);
            $table->string('ticket_subject');
            $table->integer('requested_by');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->nullable()->default('pending')->index('idx_status');
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('resolved_at')->nullable();
            $table->integer('resolved_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_deletion_requests');
    }
};
