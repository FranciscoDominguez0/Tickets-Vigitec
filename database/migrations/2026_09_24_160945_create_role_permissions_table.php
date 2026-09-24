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
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index('idx_role_permissions_empresa_id');
            $table->string('role_name', 100)->index('idx_role');
            $table->string('perm_key', 120);
            $table->boolean('is_enabled')->default(true);
            $table->dateTime('created')->nullable()->useCurrent();
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent();

            $table->index(['empresa_id', 'role_name'], 'idx_role_permissions_empresa_role');
            $table->unique(['empresa_id', 'role_name', 'perm_key'], 'uq_role_perm_empresa_role_perm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
