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
        Schema::create('staff_login_attempts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_staff_login_attempts_empresa_id');
            $table->string('username');
            $table->string('ip', 45)->nullable();
            $table->integer('attempts')->default(0);
            $table->dateTime('locked_until')->nullable();
            $table->dateTime('updated')->nullable();

            $table->index(['empresa_id', 'updated'], 'idx_staff_login_attempts_empresa_updated');
            $table->unique(['username', 'ip'], 'uq_staff_login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_login_attempts');
    }
};
