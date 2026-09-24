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
        Schema::create('email_accounts', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email')->index();
            $table->string('name')->nullable();
            $table->string('priority', 32)->nullable();
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('dept_id')->nullable()->index();
            $table->boolean('is_default')->default(false)->index();
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_secure', 10)->nullable();
            $table->string('smtp_user')->nullable();
            $table->string('smtp_pass')->nullable();
            $table->dateTime('created')->nullable()->useCurrent();
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['empresa_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_accounts');
    }
};
