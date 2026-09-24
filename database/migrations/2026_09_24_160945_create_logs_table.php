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
        Schema::create('logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->default(1)->index();
            $table->string('action', 100)->index()->comment('Acción realizada');
            $table->string('object_type', 50)->nullable()->comment('Tipo de objeto');
            $table->integer('object_id')->nullable()->comment('ID del objeto');
            $table->string('user_type', 50)->nullable();
            $table->integer('user_id')->nullable()->index()->comment('Usuario que realizó');
            $table->text('details')->nullable()->comment('Detalles de la acción');
            $table->string('ip_address', 45)->nullable()->comment('IP del usuario');
            $table->dateTime('created')->nullable()->useCurrent()->index()->comment('Fecha del evento');

            $table->index(['object_type', 'object_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
