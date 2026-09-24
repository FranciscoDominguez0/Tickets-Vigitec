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
        Schema::create('pagos_empresas', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('empresa_id')->index('idx_pago_empresa');
            $table->decimal('monto', 10);
            $table->dateTime('fecha_pago');
            $table->date('periodo_desde');
            $table->date('periodo_hasta');
            $table->string('metodo_pago', 50)->nullable();
            $table->string('referencia', 100)->nullable();
            $table->integer('registrado_por')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_empresas');
    }
};
