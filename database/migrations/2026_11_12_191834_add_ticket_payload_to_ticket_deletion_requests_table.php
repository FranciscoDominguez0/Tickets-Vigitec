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
        Schema::table('ticket_deletion_requests', function (Blueprint $table) {
            $table->longText('ticket_payload')->nullable()->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_deletion_requests', function (Blueprint $table) {
            $table->dropColumn('ticket_payload');
        });
    }
};
