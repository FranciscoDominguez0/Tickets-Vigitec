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
        Schema::create('password_resets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_password_resets_empresa_id');
            $table->integer('user_id')->index('idx_user_id');
            $table->char('token_hash', 64)->index('idx_token_hash');
            $table->dateTime('expires_at')->index('idx_expires');
            $table->dateTime('used_at')->nullable();
            $table->dateTime('created')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_resets');
    }
};
