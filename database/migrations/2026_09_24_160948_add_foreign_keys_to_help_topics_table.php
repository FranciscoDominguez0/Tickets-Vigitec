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
        Schema::table('help_topics', function (Blueprint $table) {
            $table->foreign(['dept_id'], 'fk_help_topics_dept')->references(['id'])->on('departments')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('help_topics', function (Blueprint $table) {
            $table->dropForeign('fk_help_topics_dept');
        });
    }
};
