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
        Schema::create('staff_password_resets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_staff_password_resets_empresa_id');
            $table->integer('staff_id')->index('idx_staff_id');
            $table->char('token_hash', 64)->index('idx_token_hash');
            $table->dateTime('expires_at')->index('idx_expires');
            $table->dateTime('used_at')->nullable();
            $table->dateTime('created')->nullable()->useCurrent();

            $table->index(['empresa_id', 'expires_at'], 'idx_staff_password_resets_empresa_expires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_password_resets');
    }
};
