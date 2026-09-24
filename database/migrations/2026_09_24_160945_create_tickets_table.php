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
        Schema::create('tickets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('ticket_number', 20)->index()->comment('Número visible (ej: ABC-20250126-001234)');
            $table->integer('empresa_id')->default(1)->index();
            $table->integer('user_id')->index()->comment('Usuario que creó el ticket');
            $table->integer('staff_id')->nullable()->index()->comment('Agente asignado (NULL si no asignado)');
            $table->integer('dept_id')->nullable()->default(1)->index()->comment('Departamento responsable');
            $table->integer('status_id')->nullable()->default(1)->index()->comment('Estado del ticket');
            $table->integer('priority_id')->nullable()->default(1)->index()->comment('Prioridad');
            $table->string('subject')->comment('Asunto del ticket');
            $table->string('anydesk', 50)->nullable();
            $table->dateTime('created')->nullable()->useCurrent()->index()->comment('Fecha creación');
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent()->comment('Última actualización');
            $table->dateTime('closed')->nullable()->comment('Fecha de cierre');
            $table->integer('topic_id')->nullable()->index();
            $table->string('client_signature')->nullable()->comment('Ruta del archivo PNG de firma del cliente');
            $table->text('close_message')->nullable()->comment('Motivo de cierre del ticket');
            $table->dateTime('closed_at')->nullable()->comment('Fecha y hora de cierre del ticket');
            $table->string('signature_token', 64)->nullable();
            $table->boolean('signature_requested')->nullable()->default(false);
            $table->string('walkin_phone', 50)->nullable();
            $table->string('walkin_address')->nullable();
            $table->dateTime('support_start')->nullable();
            $table->dateTime('support_end')->nullable();

            $table->index(['empresa_id', 'closed']);
            $table->index(['empresa_id', 'dept_id']);
            $table->index(['empresa_id', 'staff_id', 'closed']);
            $table->index(['empresa_id', 'status_id']);
            $table->index(['empresa_id', 'updated']);
            $table->index(['empresa_id', 'user_id']);
            $table->index(['empresa_id', 'created']);
            $table->index(['staff_id', 'status_id']);
            $table->index(['user_id', 'status_id']);
            $table->unique(['ticket_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
