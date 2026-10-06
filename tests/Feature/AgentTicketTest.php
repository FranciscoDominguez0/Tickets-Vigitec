<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Thread;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AgentTicketTest extends TestCase
{
    use DatabaseTransactions;

    protected $departamento;
    protected $prioridad;
    protected $estado;
    protected $estadoCerrado;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear datos base necesarios para los tickets
        $this->departamento = Department::firstOrCreate(['id' => 1], ['name' => 'Soporte', 'is_active' => 1]);
        $this->prioridad = Priority::firstOrCreate(['id' => 1], ['name' => 'Baja', 'id' => 1]);
        $this->estado = TicketStatus::firstOrCreate(['id' => 1], ['name' => 'Abierto', 'id' => 1]);
        $this->estadoCerrado = TicketStatus::firstOrCreate(['id' => 3], ['name' => 'Cerrado', 'id' => 3]);
    }

    public function test_agente_puede_ver_la_lista_de_tickets()
    {
        $agente = Staff::factory()->create();
        $usuario = User::factory()->create();

        Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket de prueba',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->get('/agent/tickets');

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Ticket de prueba');
    }

    public function test_agente_puede_ver_el_formulario_de_creacion_de_ticket()
    {
        $agente = Staff::factory()->create();

        $respuesta = $this->actingAs($agente, 'staff')->get('/agent/tickets/create');

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Abrir nuevo Ticket');
    }

    public function test_agente_puede_crear_un_ticket()
    {
        $agente = Staff::factory()->create(['empresa_id' => 1]);
        $usuario = User::factory()->create();

        $datosTicket = [
            'user_id' => $usuario->id,
            'subject' => 'Problema con la impresora',
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'body' => 'La impresora no enciende desde ayer.',
        ];

        $respuesta = $this->actingAs($agente, 'staff')->post('/agent/tickets', $datosTicket);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Problema con la impresora',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
        ]);

        $ticket = Ticket::where('subject', 'Problema con la impresora')->first();

        $respuesta->assertRedirect(route('agent.tickets.show', $ticket->id));
        $respuesta->assertSessionHas('success');
    }

    public function test_agente_puede_ver_un_ticket_especifico()
    {
        $agente = Staff::factory()->create();
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para ver detalle',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->get("/agent/ticket/{$ticket->id}");

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Ticket para ver detalle');
    }

    public function test_agente_puede_responder_a_un_ticket()
    {
        $agente = Staff::factory()->create(['empresa_id' => 1]);
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para responder',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->post("/agent/ticket/{$ticket->id}/reply", [
            'response_body' => 'Esta es una respuesta de prueba',
        ]);

        $this->assertDatabaseHas('thread_entries', [
            'body' => 'Esta es una respuesta de prueba',
            'staff_id' => $agente->id,
        ]);

        $respuesta->assertRedirect(route('agent.tickets.show', $ticket->id));
    }

    public function test_agente_puede_cambiar_el_estado_de_un_ticket()
    {
        $agente = Staff::factory()->create();
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para cambiar estado',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->post("/agent/ticket/{$ticket->id}/status", [
            'status_id' => $this->estadoCerrado->id,
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status_id' => $this->estadoCerrado->id,
        ]);

        $respuesta->assertRedirect();
    }

    public function test_agente_puede_asignar_un_ticket()
    {
        $agente = Staff::factory()->create();
        $agenteAsignado = Staff::factory()->create();
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para asignar',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->post("/agent/ticket/{$ticket->id}/assign", [
            'staff_id' => $agenteAsignado->id,
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'staff_id' => $agenteAsignado->id,
        ]);

        $respuesta->assertRedirect();
    }

    public function test_agente_puede_transferir_un_ticket()
    {
        $agente = Staff::factory()->create();
        $usuario = User::factory()->create();
        $nuevoDepartamento = Department::create(['name' => 'Ventas', 'is_active' => 1]);

        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para transferir',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->post("/agent/ticket/{$ticket->id}/transfer", [
            'dept_id' => $nuevoDepartamento->id,
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'dept_id' => $nuevoDepartamento->id,
        ]);

        $respuesta->assertRedirect();
    }

    public function test_agente_puede_eliminar_un_ticket()
    {
        $agente = Staff::factory()->create();
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para eliminar',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->delete("/agent/ticket/{$ticket->id}");

        $this->assertDatabaseMissing('tickets', [
            'id' => $ticket->id,
        ]);

        $respuesta->assertRedirect(route('agent.tickets.index'));
    }

    public function test_agente_puede_actualizar_un_hilo()
    {
        $agente = Staff::factory()->create();

        $usuario = User::factory()->create();
        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para hilo',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $hilo = Thread::create(['ticket_id' => $ticket->id, 'created' => now()]);

        $entrada = ThreadEntry::create([
            'thread_id' => $hilo->id,
            'empresa_id' => 1,
            'staff_id' => $agente->id,
            'body' => 'Texto original',
            'is_internal' => 0,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->put("/agent/thread/{$entrada->id}", [
            'body' => 'Texto actualizado',
        ]);

        $this->assertDatabaseHas('thread_entries', [
            'id' => $entrada->id,
            'body' => 'Texto actualizado',
        ]);

        $respuesta->assertRedirect();
    }

    public function test_agente_puede_eliminar_un_hilo()
    {
        $agente = Staff::factory()->create();

        $usuario = User::factory()->create();
        $ticket = Ticket::create([
            'ticket_number' => '12345',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para hilo',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $hilo = Thread::create(['ticket_id' => $ticket->id, 'created' => now()]);

        $entrada = ThreadEntry::create([
            'thread_id' => $hilo->id,
            'empresa_id' => 1,
            'staff_id' => $agente->id,
            'body' => 'Texto a eliminar',
            'is_internal' => 0,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->delete("/agent/thread/{$entrada->id}");

        $this->assertDatabaseMissing('thread_entries', [
            'id' => $entrada->id,
        ]);

        $respuesta->assertRedirect();
    }
}
