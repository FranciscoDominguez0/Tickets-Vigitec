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
        Schema::create('help_topics', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->index('idx_name');
            $table->text('description')->nullable();
            $table->integer('empresa_id')->default(1)->index('idx_help_topics_empresa_id');
            $table->integer('dept_id')->nullable()->index('idx_dept_id');
            $table->tinyInteger('is_active')->nullable()->default(1)->index('idx_active');
            $table->dateTime('created')->nullable()->useCurrent();
            $table->boolean('is_public')->nullable()->default(true);

            $table->index(['empresa_id', 'is_active'], 'idx_help_topics_empresa_active');
            $table->unique(['name'], 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_topics');
    }
};
