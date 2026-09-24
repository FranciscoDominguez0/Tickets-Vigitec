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
        Schema::create('ticket_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empresa_id')->default(1)->index('idx_empresa_id');
            $table->integer('ticket_id')->unique('idx_ticket_id');
            $table->text('work_description');
            $table->text('observations')->nullable();
            $table->string('final_price', 50)->nullable();
            $table->string('billing_status', 50)->nullable()->default('pending')->index('idx_tr_billing');
            $table->integer('created_by');
            $table->dateTime('created_at')->useCurrent();

            $table->index(['ticket_id'], 'idx_tr_ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_reports');
    }
};
