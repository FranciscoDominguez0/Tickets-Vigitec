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
        Schema::table('staff_departments', function (Blueprint $table) {
            $table->foreign(['dept_id'], 'fk_sd_department')->references(['id'])->on('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['staff_id'], 'fk_sd_staff')->references(['id'])->on('staff')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_departments', function (Blueprint $table) {
            $table->dropForeign('fk_sd_department');
            $table->dropForeign('fk_sd_staff');
        });
    }
};
