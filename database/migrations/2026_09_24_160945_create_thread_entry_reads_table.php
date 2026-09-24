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
        Schema::create('thread_entry_reads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id')->default(1)->index('idx_empresa');
            $table->unsignedInteger('thread_entry_id')->index('idx_thread_entry');
            $table->enum('read_by', ['user', 'staff'])->comment('Quién leyó (lado opuesto al autor)');
            $table->unsignedInteger('reader_id')->default(0)->comment('user_id o staff_id; 0 = cualquier agente');
            $table->dateTime('read_at')->useCurrent();

            $table->unique(['thread_entry_id', 'read_by', 'reader_id'], 'uk_entry_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_entry_reads');
    }
};
