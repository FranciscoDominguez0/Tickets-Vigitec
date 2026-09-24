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
        Schema::create('billing_notice_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index();
            $table->integer('days_before');
            $table->date('fecha_vencimiento');
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['empresa_id', 'days_before', 'fecha_vencimiento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_notice_log');
    }
};
