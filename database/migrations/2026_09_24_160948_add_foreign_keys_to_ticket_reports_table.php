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
        Schema::table('ticket_reports', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'fk_report_empresa')->references(['id'])->on('empresas')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['ticket_id'], 'fk_report_ticket')->references(['id'])->on('tickets')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_reports', function (Blueprint $table) {
            $table->dropForeign('fk_report_empresa');
            $table->dropForeign('fk_report_ticket');
        });
    }
};
