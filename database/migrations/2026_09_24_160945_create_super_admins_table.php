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
        Schema::create('super_admins', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('password');
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('dark_mode')->nullable()->default(false);
            $table->dateTime('created')->nullable()->useCurrent();
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->dateTime('last_login')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('super_admins');
    }
};
