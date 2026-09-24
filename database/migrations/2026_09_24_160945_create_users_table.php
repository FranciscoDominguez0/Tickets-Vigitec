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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('email')->unique('email')->comment('Email único del usuario');
            $table->string('password')->comment('Hash bcrypt de la contraseña');
            $table->string('firstname', 100)->comment('Primer nombre');
            $table->string('lastname', 100)->comment('Apellido');
            $table->string('phone', 20)->nullable()->comment('Teléfono del contacto');
            $table->string('company', 100)->nullable()->comment('Empresa/Compañía');
            $table->integer('empresa_id')->default(1)->index('idx_users_empresa_id');
            $table->enum('status', ['active', 'inactive', 'banned'])->nullable()->default('active')->index('idx_status')->comment('Estado del usuario');
            $table->dateTime('created')->nullable()->useCurrent()->index('idx_created')->comment('Fecha de creación');
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent()->comment('Última actualización');
            $table->dateTime('last_login')->nullable()->comment('Último acceso');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('dark_mode')->default(false);
            $table->boolean('org_tickets_view')->default(false)->comment('1 = puede explorar tickets por organización en el portal');

            $table->index(['email'], 'idx_email');
            $table->index(['empresa_id', 'created'], 'idx_u_emp_created');
            $table->index(['empresa_id', 'email'], 'idx_u_emp_email');
            $table->index(['empresa_id', 'status'], 'idx_u_emp_status');
            $table->index(['empresa_id', 'status'], 'idx_users_empresa_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
