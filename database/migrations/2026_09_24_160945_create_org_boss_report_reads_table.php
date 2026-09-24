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
        Schema::create('org_boss_report_reads', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('report_id');
            $table->integer('user_id')->index();
            $table->dateTime('read_at')->useCurrent();

            $table->unique(['report_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_boss_report_reads');
    }
};
