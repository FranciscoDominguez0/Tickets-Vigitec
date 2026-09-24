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
        Schema::table('staff_password_resets', function (Blueprint $table) {
            $table->foreign(['staff_id'], 'staff_password_resets_ibfk_1')->references(['id'])->on('staff')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_password_resets', function (Blueprint $table) {
            $table->dropForeign('staff_password_resets_ibfk_1');
        });
    }
};
