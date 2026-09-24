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
        Schema::create('config', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index();
            $table->string('config_key', 100)->unique()->comment('Clave de configuración');
            $table->longText('config_value')->nullable()->comment('Valor');
            $table->text('description')->nullable()->comment('Descripción');
            $table->dateTime('created')->nullable()->useCurrent()->comment('Creación');

            $table->index(['config_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config');
    }
};
