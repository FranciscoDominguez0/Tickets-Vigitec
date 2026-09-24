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
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('staff_id')->index();
            $table->char('token_hash', 64)->index();
            $table->dateTime('expires_at')->index();
            $table->dateTime('used_at')->nullable();
            $table->dateTime('created')->nullable()->useCurrent();

            $table->index(['empresa_id', 'expires_at']);
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
