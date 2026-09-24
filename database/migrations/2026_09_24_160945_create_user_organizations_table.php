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
        Schema::create('user_organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('empresa_id')->default(1);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('organization_id')->index();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['empresa_id', 'user_id']);
            $table->unique(['user_id', 'organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_organizations');
    }
};
