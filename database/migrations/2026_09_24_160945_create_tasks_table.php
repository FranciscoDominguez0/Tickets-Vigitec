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
        Schema::create('tasks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('assigned_to')->nullable()->index();
            $table->integer('created_by')->nullable()->index();
            $table->integer('dept_id');
            $table->integer('empresa_id')->default(1)->index();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->nullable()->default('pending');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->nullable()->default('normal');
            $table->dateTime('due_date')->nullable();
            $table->dateTime('created')->nullable()->useCurrent();
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['empresa_id', 'created']);
            $table->index(['empresa_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
