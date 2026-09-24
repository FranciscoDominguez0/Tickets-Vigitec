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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('empresa_id_idx');
            $table->string('actor_type', 50);
            $table->integer('actor_id');
            $table->string('action', 100);
            $table->string('entity_type', 50)->nullable();
            $table->integer('entity_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->dateTime('created_at')->index('created_at_idx');

            $table->index(['actor_type', 'actor_id'], 'actor_idx');
            $table->index(['entity_type', 'entity_id'], 'entity_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
