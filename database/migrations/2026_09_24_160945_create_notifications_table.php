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
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_notifications_empresa_id');
            $table->integer('staff_id');
            $table->text('message');
            $table->string('type', 50)->nullable()->default('general');
            $table->integer('related_id')->nullable();
            $table->boolean('is_read')->nullable()->default(false);
            $table->dateTime('created_at')->nullable()->useCurrent()->index('idx_created');

            $table->index(['staff_id', 'is_read'], 'idx_staff_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
