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
        Schema::create('staff', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('username')->index('idx_username')->comment('Nombre de usuario único');
            $table->string('password')->comment('Hash bcrypt de la contraseña');
            $table->string('email')->unique('email')->comment('Email del agente');
            $table->string('firstname', 100)->comment('Primer nombre');
            $table->string('lastname', 100)->comment('Apellido');
            $table->integer('dept_id')->nullable()->index('idx_dept_id')->comment('Departamento asignado');
            $table->integer('empresa_id')->default(1)->index('idx_staff_empresa_id');
            $table->string('role', 100)->nullable()->default('agent');
            $table->tinyInteger('is_active')->nullable()->default(1)->index('idx_active')->comment('1=activo, 0=inactivo');
            $table->dateTime('created')->nullable()->useCurrent()->comment('Fecha de creación');
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent()->comment('Última actualización');
            $table->dateTime('last_login')->nullable()->comment('Último acceso');
            $table->text('signature')->nullable()->comment('Firma opcional en respuestas');
            $table->boolean('dark_mode')->nullable()->default(false);

            $table->index(['email'], 'idx_email');
            $table->index(['empresa_id', 'is_active'], 'idx_staff_empresa_active');
            $table->unique(['username'], 'username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
