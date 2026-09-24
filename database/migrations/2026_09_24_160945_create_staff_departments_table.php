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
        Schema::create('staff_departments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('staff_id')->index('idx_sd_staff_id');
            $table->integer('dept_id')->index('idx_sd_dept_id');
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['staff_id', 'dept_id'], 'uq_staff_departments_original_pk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_departments');
    }
};
