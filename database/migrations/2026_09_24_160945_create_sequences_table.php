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
        Schema::create('sequences', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_sequences_empresa_id');
            $table->string('name', 100)->unique('name');
            $table->bigInteger('next')->default(1);
            $table->integer('increment')->default(1);
            $table->integer('padding')->default(0);
            $table->dateTime('created');
            $table->dateTime('updated')->nullable();

            $table->unique(['empresa_id', 'name'], 'uq_sequences_empresa_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sequences');
    }
};
