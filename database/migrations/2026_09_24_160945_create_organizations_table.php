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
        Schema::create('organizations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name')->index('idx_name');
            $table->integer('empresa_id')->default(1)->index('idx_organizations_empresa_id');
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('phone_ext', 20)->nullable();
            $table->string('website')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('created')->nullable()->useCurrent();
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent();
            $table->boolean('plain_text_emails')->default(false);

            $table->unique(['name'], 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
