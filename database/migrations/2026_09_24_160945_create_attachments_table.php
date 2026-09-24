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
        Schema::create('attachments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('thread_entry_id')->nullable()->index()->comment('Mensaje asociado');
            $table->integer('empresa_id')->default(1)->index();
            $table->string('filename')->comment('Nombre en servidor');
            $table->string('original_filename')->nullable()->comment('Nombre original subido');
            $table->string('mimetype', 100)->nullable()->comment('Tipo MIME');
            $table->integer('size')->nullable()->comment('Tamaño en bytes');
            $table->string('path', 500)->nullable()->comment('Ruta del archivo');
            $table->string('hash', 64)->nullable()->comment('Hash SHA256');
            $table->dateTime('created')->nullable()->useCurrent()->index()->comment('Fecha upload');

            $table->index(['empresa_id', 'created']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
