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
        Schema::create('departments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->index('idx_name')->comment('Nombre del departamento');
            $table->text('description')->nullable()->comment('Descripción');
            $table->integer('empresa_id')->default(1)->index('idx_departments_empresa_id');
            $table->tinyInteger('is_active')->nullable()->default(1)->index('idx_active')->comment('1=activo, 0=inactivo');
            $table->dateTime('created')->nullable()->useCurrent()->comment('Fecha de creación');
            $table->integer('default_staff_id')->nullable();
            $table->boolean('requires_report')->default(false);

            $table->index(['empresa_id', 'requires_report'], 'idx_d_emp_requires');
            $table->index(['empresa_id', 'is_active'], 'idx_departments_empresa_active');
            $table->unique(['name'], 'name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
