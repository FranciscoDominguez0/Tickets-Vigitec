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
        Schema::create('ticket_approvals', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('ticket_id');
            $table->integer('requested_by_staff_id');
            $table->integer('manager_id')->nullable();
            $table->enum('status', ['pending', 'cotizacion', 'aprobado', 'rechazado'])->nullable()->default('pending');
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('resolved_at')->nullable();

            $table->index(['ticket_id', 'id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_approvals');
    }
};
