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
        Schema::create('user_notes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_user_notes_empresa_id');
            $table->integer('user_id');
            $table->integer('staff_id')->nullable()->index('idx_staff');
            $table->text('note');
            $table->dateTime('created')->useCurrent();
            $table->dateTime('updated')->nullable();

            $table->index(['user_id', 'created'], 'idx_user_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notes');
    }
};
