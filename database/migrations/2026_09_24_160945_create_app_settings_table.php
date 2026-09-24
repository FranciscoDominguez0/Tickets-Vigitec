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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key', 191);
            $table->integer('empresa_id')->default(1)->index();
            $table->longText('value')->nullable();
            $table->dateTime('updated')->nullable();

            $table->unique(['empresa_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
