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
        Schema::table('ticket_report_items', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'fk_report_item_empresa')->references(['id'])->on('empresas')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_report_items', function (Blueprint $table) {
            $table->dropForeign('fk_report_item_empresa');
        });
    }
};
