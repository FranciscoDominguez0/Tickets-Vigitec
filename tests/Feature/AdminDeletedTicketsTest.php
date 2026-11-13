<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Ticket;
use App\Models\TicketDeletionRequest;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminDeletedTicketsTest extends TestCase
{
    use DatabaseTransactions;

    protected Department $departamento;

    protected Priority $prioridad;

    protected TicketStatus $estado;

    protected Staff $administrador;

    protected Staff $agente;

    protected User $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->departamento = Department::firstOrCreate(['id' => 1], [
            'name' => 'Soporte Técnico',
            'empresa_id' => 1,
            'is_active' => 1,
        ]);

        $this->prioridad = Priority::firstOrCreate(['id' => 1], [
            'name' => 'Media',
            'level' => 1,
            'color' => '#3498db',
        ]);

        $this->estado = TicketStatus::firstOrCreate(['id' => 1], [
            'name' => 'Abierto',
            'color' => '#3498db',
            'order_by' => 1,
        ]);

        $this->administrador = Staff::factory()->create([
            'role' => 'admin',
            'empresa_id' => 1,
            'is_active' => 1,
        ]);

        $this->agente = Staff::factory()->create([
            'role' => 'agent',
            'empresa_id' => 1,
            'is_active' => 1,
        ]);

        $this->cliente = User::factory()->create([
            'empresa_id' => 1,
        ]);
    }

    /**
     * Verifica que al eliminar un ticket se archive su instantánea y motivo en el historial de borrados.
     */
    public function test_eliminar_ticket_registra_en_historial_de_borrados(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-DEL-001',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'staff_id' => $this->agente->id,
            'subject' => 'Ticket para prueba de eliminación',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.tickets.destroy', $ticket->id), [
                'motivo' => 'Ticket duplicado generado por error del cliente.',
            ]);

        $respuesta->assertRedirect(route('agent.tickets.index'));
        $respuesta->assertSessionHas('success');

        // El ticket ya no debe estar activo en la tabla de tickets
        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);

        // Debe existir el registro en la tabla de tickets borrados
        $this->assertDatabaseHas('ticket_deletion_requests', [
            'ticket_number' => 'TCK-DEL-001',
            'ticket_subject' => 'Ticket para prueba de eliminación',
            'requested_by' => $this->agente->id,
            'reason' => 'Ticket duplicado generado por error del cliente.',
            'status' => 'approved',
        ]);
    }

    /**
     * Verifica que el administrador pueda listar los tickets borrados en /admin/deleted-tickets.
     */
    public function test_administrador_puede_listar_tickets_borrados(): void
    {
        TicketDeletionRequest::create([
            'ticket_id' => 9991,
            'empresa_id' => 1,
            'ticket_number' => 'TCK-LIST-999',
            'ticket_subject' => 'Asunto de ticket archivado',
            'requested_by' => $this->agente->id,
            'reason' => 'Prueba de visualización en panel admin',
            'status' => 'approved',
            'created_at' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->get(route('admin.deleted_tickets'));

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Historial de Tickets Borrados');
        $respuesta->assertSee('TCK-LIST-999');
        $respuesta->assertSee('Asunto de ticket archivado');
        $respuesta->assertSee('Prueba de visualización en panel admin');
    }

    /**
     * Verifica que el administrador pueda restaurar un ticket borrado.
     */
    public function test_administrador_puede_restaurar_ticket_borrado(): void
    {
        $payload = [
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'staff_id' => $this->agente->id,
            'subject' => 'Ticket restaurable',
            'thread_entries' => [
                [
                    'user_id' => $this->cliente->id,
                    'body' => 'Mensaje inicial de prueba que debe restaurarse.',
                    'is_internal' => 0,
                ],
            ],
        ];

        $solicitud = TicketDeletionRequest::create([
            'ticket_id' => 8882,
            'empresa_id' => 1,
            'ticket_number' => 'TCK-RESTORE-888',
            'ticket_subject' => 'Ticket restaurable',
            'requested_by' => $this->agente->id,
            'reason' => 'Eliminado por equivocación',
            'status' => 'approved',
            'ticket_payload' => $payload,
            'created_at' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->post(route('admin.deleted_tickets.restore', $solicitud->id));

        $respuesta->assertRedirect(route('admin.deleted_tickets'));
        $respuesta->assertSessionHas('success');

        // El ticket debe haber vuelto a la tabla de tickets
        $this->assertDatabaseHas('tickets', [
            'ticket_number' => 'TCK-RESTORE-888',
            'subject' => 'Ticket restaurable',
            'user_id' => $this->cliente->id,
        ]);

        // La solicitud debe quedar marcada como restaurada (rejected)
        $solicitud->refresh();
        $this->assertEquals('rejected', $solicitud->status);
    }

    /**
     * Verifica que el administrador pueda purgar definitivamente un registro de auditoría.
     */
    public function test_administrador_puede_purgar_registro_de_borrado(): void
    {
        $solicitud = TicketDeletionRequest::create([
            'ticket_id' => 7773,
            'empresa_id' => 1,
            'ticket_number' => 'TCK-PURGE-777',
            'ticket_subject' => 'Ticket para purgar',
            'requested_by' => $this->agente->id,
            'reason' => 'Eliminación permanente solicitada',
            'status' => 'approved',
            'created_at' => now(),
        ]);

        $respuesta = $this->actingAs($this->administrador, 'staff')
            ->delete(route('admin.deleted_tickets.purge', $solicitud->id));

        $respuesta->assertRedirect(route('admin.deleted_tickets'));
        $respuesta->assertSessionHas('success');

        $this->assertDatabaseMissing('ticket_deletion_requests', [
            'id' => $solicitud->id,
        ]);
    }
}
