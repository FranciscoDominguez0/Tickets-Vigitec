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
        Schema::create('user_login_attempts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_user_login_attempts_empresa_id');
            $table->string('email');
            $table->string('ip', 45)->nullable();
            $table->integer('attempts')->default(0);
            $table->dateTime('locked_until')->nullable();
            $table->dateTime('updated')->nullable();

            $table->index(['empresa_id', 'updated'], 'idx_user_login_attempts_empresa_updated');
            $table->unique(['email', 'ip'], 'uq_user_login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_attempts');
    }
};
