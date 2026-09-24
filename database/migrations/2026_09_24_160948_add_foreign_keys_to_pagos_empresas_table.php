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
        Schema::table('pagos_empresas', function (Blueprint $table) {
            $table->foreign(['empresa_id'], 'fk_pagos_empresas_empresa')->references(['id'])->on('empresas')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos_empresas', function (Blueprint $table) {
            $table->dropForeign('fk_pagos_empresas_empresa');
        });
    }
};
