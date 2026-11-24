<?php

namespace Tests\Feature;

use App\Models\Banlist;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Requisition;
use App\Models\Staff;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketCollaborator;
use App\Models\TicketLink;
use App\Models\TicketReferral;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AgentTicketOptionsTest extends TestCase
{
    use DatabaseTransactions;

    protected Staff $agente;

    protected User $cliente;

    protected Ticket $ticket;

    protected Department $departamento;

    protected function setUp(): void
    {
        parent::setUp();

        $this->departamento = Department::firstOrCreate(['id' => 1], ['name' => 'Soporte', 'is_active' => 1, 'empresa_id' => 1]);
        $prioridad = Priority::firstOrCreate(['id' => 1], ['name' => 'Baja', 'id' => 1]);
        $estado = TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto', 'id' => 1]);
        TicketStatus::firstOrCreate(['id' => 5], ['name' => 'Cerrado', 'id' => 5]);

        $this->agente = Staff::factory()->create([
            'empresa_id' => 1,
            'role' => 'admin',
            'is_active' => 1,
        ]);

        $this->cliente = User::factory()->create([
            'empresa_id' => 1,
            'status' => 1,
            'email' => 'cliente_ticket@example.com',
        ]);

        $this->ticket = Ticket::create([
            'ticket_number' => '99901',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $prioridad->id,
            'status_id' => $estado->id,
            'subject' => 'Ticket de Prueba para Opciones',
            'empresa_id' => 1,
            'created' => now(),
            'updated' => now(),
        ]);
    }

    public function test_agente_puede_ver_las_opciones_en_la_vista_del_ticket(): void
    {
        $respuesta = $this->actingAs($this->agente, 'staff')
            ->get(route('agent.tickets.show', $this->ticket->id));

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Cambiar Propietario');
        $respuesta->assertSee('Unir Tiquetes');
        $respuesta->assertSee('Tickets vinculados');
        $respuesta->assertSee('Editar horas de soporte');
        $respuesta->assertSee('Administrar referidos');
        $respuesta->assertSee('Solicitud de Inventario');
        $respuesta->assertSee('Gestionar Colaboradores');
        $respuesta->assertSee('Solicitar revisión ejecutiva');
        $respuesta->assertSee('Bloquear Email');
        $respuesta->assertSee('Borrar Ticket');
    }

    public function test_agente_puede_cambiar_propietario_del_ticket(): void
    {
        $nuevoCliente = User::factory()->create([
            'empresa_id' => 1,
            'firstname' => 'Carlos',
            'lastname' => 'Nuevo Propietario',
            'email' => 'carlos@empresa.com',
        ]);

        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.change_owner', $this->ticket->id), [
                'user_id' => $nuevoCliente->id,
                'motivo' => 'Transferencia de cuenta de servicio.',
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $this->ticket->refresh();
        $this->assertEquals($nuevoCliente->id, $this->ticket->user_id);

        $hilo = $this->ticket->thread;
        $this->assertNotNull($hilo);
        $this->assertTrue(
            $hilo->entries()
                ->where('is_internal', 1)
                ->where('body', 'like', '%Propietario del ticket cambiado%')
                ->exists()
        );
    }

    public function test_agente_puede_unir_tickets(): void
    {
        $ticketSecundario = Ticket::create([
            'ticket_number' => '99902',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => 1,
            'status_id' => 1,
            'subject' => 'Ticket Duplicado Secundario',
            'empresa_id' => 1,
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.merge', $this->ticket->id), [
                'ticket_secundario_id' => $ticketSecundario->ticket_number,
                'motivo' => 'Incidente reportado por duplicado.',
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $ticketSecundario->refresh();
        $this->assertEquals(5, $ticketSecundario->status_id);
        $this->assertNotNull($ticketSecundario->closed);

        $this->assertDatabaseHas('ticket_links', [
            'ticket_id' => $this->ticket->id,
            'linked_ticket_id' => $ticketSecundario->id,
        ]);
    }

    public function test_agente_puede_vincular_y_desvincular_tickets(): void
    {
        $otroTicket = Ticket::create([
            'ticket_number' => '99903',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => 1,
            'status_id' => 1,
            'subject' => 'Ticket Relacionado',
            'empresa_id' => 1,
            'created' => now(),
            'updated' => now(),
        ]);

        // 1. Vincular
        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.link', $this->ticket->id), [
                'ticket_vinculado_id' => $otroTicket->id,
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $vinculo = TicketLink::where('ticket_id', $this->ticket->id)
            ->where('linked_ticket_id', $otroTicket->id)
            ->first();
        $this->assertNotNull($vinculo);

        // 2. Desvincular
        $respuestaDesvincular = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.tickets.unlink', [$this->ticket->id, $vinculo->id]));

        $respuestaDesvincular->assertRedirect();
        $respuestaDesvincular->assertSessionHas('success');

        $this->assertDatabaseMissing('ticket_links', [
            'id' => $vinculo->id,
        ]);
    }

    public function test_agente_puede_actualizar_horas_de_soporte(): void
    {
        $inicio = now()->subHours(2)->format('Y-m-d H:i:s');
        $fin = now()->format('Y-m-d H:i:s');

        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.support_hours', $this->ticket->id), [
                'support_start' => $inicio,
                'support_end' => $fin,
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $this->ticket->refresh();
        $this->assertNotNull($this->ticket->support_start);
        $this->assertNotNull($this->ticket->support_end);
    }

    public function test_agente_puede_agregar_y_eliminar_referidos(): void
    {
        $colega = Staff::factory()->create(['empresa_id' => 1, 'is_active' => 1]);

        // 1. Agregar referido
        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.refer', $this->ticket->id), [
                'staff_id' => $colega->id,
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $referido = TicketReferral::where('ticket_id', $this->ticket->id)
            ->where('staff_id', $colega->id)
            ->first();
        $this->assertNotNull($referido);

        // 2. Eliminar referido
        $respuestaEliminar = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.tickets.unrefer', [$this->ticket->id, $referido->id]));

        $respuestaEliminar->assertRedirect();
        $respuestaEliminar->assertSessionHas('success');

        $this->assertDatabaseMissing('ticket_referrals', [
            'id' => $referido->id,
        ]);
    }

    public function test_agente_puede_crear_solicitud_de_inventario(): void
    {
        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.requisition', $this->ticket->id), [
                'items' => [
                    ['product_name' => 'Cable HDMI 2.0', 'quantity' => 2],
                    ['product_name' => 'Conector RJ45 Cat6', 'quantity' => 10],
                ],
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $requisicion = Requisition::where('ticket_id', $this->ticket->id)->first();
        $this->assertNotNull($requisicion);
        $this->assertEquals('pending', $requisicion->status);
        $this->assertCount(2, $requisicion->items);
    }

    public function test_agente_puede_gestionar_colaboradores(): void
    {
        $correoColaborador = 'colaborador.externo@empresa.com';

        // 1. Agregar colaborador
        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.collaborators.store', $this->ticket->id), [
                'email' => $correoColaborador,
                'name' => 'Ingeniero Externo',
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $colaborador = TicketCollaborator::where('ticket_id', $this->ticket->id)
            ->where('email', $correoColaborador)
            ->first();
        $this->assertNotNull($colaborador);

        // 2. Eliminar colaborador
        $respuestaEliminar = $this->actingAs($this->agente, 'staff')
            ->delete(route('agent.tickets.collaborators.destroy', [$this->ticket->id, $colaborador->id]));

        $respuestaEliminar->assertRedirect();
        $respuestaEliminar->assertSessionHas('success');

        $this->assertDatabaseMissing('ticket_collaborators', [
            'id' => $colaborador->id,
        ]);
    }

    public function test_agente_puede_solicitar_revision_ejecutiva(): void
    {
        $gerente = Staff::factory()->create([
            'empresa_id' => 1,
            'role' => 'admin',
            'is_active' => 1,
        ]);

        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.request_approval', $this->ticket->id), [
                'manager_id' => $gerente->id,
                'motivo' => 'Se requiere autorización para reemplazo de tarjeta madre fuera de cobertura.',
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $aprobacion = TicketApproval::where('ticket_id', $this->ticket->id)
            ->where('manager_id', $gerente->id)
            ->first();

        $this->assertNotNull($aprobacion);
        $this->assertEquals('pending', $aprobacion->status);
    }

    public function test_agente_puede_bloquear_email_del_cliente(): void
    {
        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post(route('agent.tickets.ban_email', $this->ticket->id), [
                'motivo' => 'Remitente genera spam reiterado.',
                'bloquear_dominio' => 0,
            ]);

        $respuesta->assertRedirect();
        $respuesta->assertSessionHas('success');

        $bloqueo = Banlist::where('empresa_id', 1)
            ->where('email', $this->cliente->email)
            ->first();

        $this->assertNotNull($bloqueo);
        $this->assertTrue($bloqueo->is_active);
    }
}
