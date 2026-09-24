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
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('ticket_id')->unique();
            $table->text('work_description');
            $table->text('observations')->nullable();
            $table->string('final_price', 50)->nullable();
            $table->string('billing_status', 50)->nullable()->default('pending')->index();
            $table->integer('created_by');
            $table->dateTime('created_at')->useCurrent();

            $table->index(['ticket_id']);
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
