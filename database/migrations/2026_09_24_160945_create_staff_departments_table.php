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
            $table->integer('staff_id')->index();
            $table->integer('dept_id')->index();
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['staff_id', 'dept_id']);
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
