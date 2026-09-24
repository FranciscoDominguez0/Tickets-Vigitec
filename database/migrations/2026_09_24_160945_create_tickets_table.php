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
            $table->string('ticket_number', 20)->index('idx_ticket_number')->comment('Número visible (ej: ABC-20250126-001234)');
            $table->integer('empresa_id')->default(1)->index('idx_tickets_empresa_id');
            $table->integer('user_id')->index('idx_user_id')->comment('Usuario que creó el ticket');
            $table->integer('staff_id')->nullable()->index('idx_staff_id')->comment('Agente asignado (NULL si no asignado)');
            $table->integer('dept_id')->nullable()->default(1)->index('dept_id')->comment('Departamento responsable');
            $table->integer('status_id')->nullable()->default(1)->index('idx_status_id')->comment('Estado del ticket');
            $table->integer('priority_id')->nullable()->default(1)->index('priority_id')->comment('Prioridad');
            $table->string('subject')->comment('Asunto del ticket');
            $table->string('anydesk', 50)->nullable();
            $table->dateTime('created')->nullable()->useCurrent()->index('idx_created')->comment('Fecha creación');
            $table->dateTime('updated')->useCurrentOnUpdate()->nullable()->useCurrent()->comment('Última actualización');
            $table->dateTime('closed')->nullable()->comment('Fecha de cierre');
            $table->integer('topic_id')->nullable()->index('idx_topic_id');
            $table->string('client_signature')->nullable()->comment('Ruta del archivo PNG de firma del cliente');
            $table->text('close_message')->nullable()->comment('Motivo de cierre del ticket');
            $table->dateTime('closed_at')->nullable()->comment('Fecha y hora de cierre del ticket');
            $table->string('signature_token', 64)->nullable();
            $table->boolean('signature_requested')->nullable()->default(false);
            $table->string('walkin_phone', 50)->nullable();
            $table->string('walkin_address')->nullable();
            $table->dateTime('support_start')->nullable();
            $table->dateTime('support_end')->nullable();

            $table->index(['empresa_id', 'closed'], 'idx_emp_closed');
            $table->index(['empresa_id', 'dept_id'], 'idx_emp_dept');
            $table->index(['empresa_id', 'staff_id', 'closed'], 'idx_emp_staff_cl');
            $table->index(['empresa_id', 'status_id'], 'idx_emp_status');
            $table->index(['empresa_id', 'updated'], 'idx_emp_updated');
            $table->index(['empresa_id', 'user_id'], 'idx_emp_user');
            $table->index(['empresa_id', 'created'], 'idx_tickets_empresa_created');
            $table->index(['empresa_id', 'status_id'], 'idx_tickets_empresa_status');
            $table->index(['staff_id', 'status_id'], 'idx_tickets_staff_status');
            $table->index(['user_id', 'status_id'], 'idx_tickets_user_status');
            $table->unique(['ticket_number'], 'ticket_number');
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
