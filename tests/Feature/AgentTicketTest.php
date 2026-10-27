<?php

namespace Tests\Feature;

use App\DataTransferObjects\Ticket\TicketData;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Thread;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Repositories\TicketRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_agente_puede_responder_al_hilo_del_ticket_con_archivos_adjuntos(): void
    {
        Storage::fake();

        $agente = Staff::factory()->create(['empresa_id' => 1]);
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '445566',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket con adjuntos de prueba',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $archivoAdjunto = UploadedFile::fake()->create('foto_evidencia.jpg', 250, 'image/jpeg');

        $respuesta = $this->actingAs($agente, 'staff')->post("/agent/ticket/{$ticket->id}/reply", [
            'response_body' => 'Adjunto la evidencia solicitada para resolver el ticket.',
            'attachments' => [$archivoAdjunto],
        ]);

        $respuesta->assertRedirect(route('agent.tickets.show', $ticket->id));

        $this->assertDatabaseHas('thread_entries', [
            'body' => 'Adjunto la evidencia solicitada para resolver el ticket.',
            'staff_id' => $agente->id,
        ]);

        $this->assertDatabaseHas('attachments', [
            'original_filename' => 'foto_evidencia.jpg',
            'mimetype' => 'image/jpeg',
        ]);
    }

    public function test_falla_al_responder_al_hilo_si_el_cuerpo_del_mensaje_esta_vacio(): void
    {
        $agente = Staff::factory()->create(['empresa_id' => 1]);
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '112233',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estado->id,
            'subject' => 'Ticket para probar validación',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($agente, 'staff')->post("/agent/ticket/{$ticket->id}/reply", [
            'response_body' => '',
        ]);

        $respuesta->assertSessionHasErrors(['response_body']);
    }

    public function test_creacion_de_ticket_con_cuerpo_genera_hilo_y_mensaje_inicial_correctamente(): void
    {
        $agente = Staff::factory()->create(['empresa_id' => 1]);
        $usuario = User::factory()->create();

        $datos = [
            'user_id' => $usuario->id,
            'subject' => 'Fallo en servidor principal',
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'body' => 'El servidor no responde a peticiones HTTP.',
        ];

        $respuesta = $this->actingAs($agente, 'staff')->post('/agent/tickets', $datos);

        $ticket = Ticket::where('subject', 'Fallo en servidor principal')->first();
        $this->assertNotNull($ticket);
        $respuesta->assertRedirect(route('agent.tickets.show', $ticket->id));

        $this->assertDatabaseHas('threads', [
            'ticket_id' => $ticket->id,
        ]);

        $this->assertDatabaseHas('thread_entries', [
            'body' => 'El servidor no responde a peticiones HTTP.',
            'staff_id' => $agente->id,
        ]);
    }

    public function test_falla_validacion_al_crear_ticket_sin_campos_obligatorios(): void
    {
        $agente = Staff::factory()->create(['empresa_id' => 1]);

        $respuesta = $this->actingAs($agente, 'staff')->post('/agent/tickets', []);

        $respuesta->assertSessionHasErrors(['user_id', 'subject', 'dept_id', 'priority_id']);
    }

    public function test_falla_validacion_al_crear_ticket_con_referencias_inexistentes(): void
    {
        $agente = Staff::factory()->create(['empresa_id' => 1]);

        $respuesta = $this->actingAs($agente, 'staff')->post('/agent/tickets', [
            'user_id' => 999999,
            'subject' => 'Ticket inválido',
            'dept_id' => 999999,
            'priority_id' => 999999,
        ]);

        $respuesta->assertSessionHasErrors(['user_id', 'dept_id', 'priority_id']);
    }

    public function test_agente_puede_guardar_firma_del_cliente_en_base64_y_cerrar_ticket(): void
    {
        Storage::fake('public');

        $agente = Staff::factory()->create(['empresa_id' => 1]);
        $usuario = User::factory()->create();

        $ticket = Ticket::create([
            'ticket_number' => '778899',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => 1,
            'subject' => 'Ticket para firma y cierre',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $firmaFalsaBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $respuesta = $this->actingAs($agente, 'staff')->post("/agent/ticket/{$ticket->id}/request-signature", [
            'firma_base64' => $firmaFalsaBase64,
            'status_id' => 3,
        ]);

        $respuesta->assertRedirect();
        $ticketActualizado = $ticket->fresh();

        $this->assertEquals(3, $ticketActualizado->status_id);
        $this->assertNotNull($ticketActualizado->client_signature);
    }

    public function test_ticket_data_dto_se_mapea_correctamente_para_el_repositorio(): void
    {
        $dto = new TicketData(
            userId: 5,
            subject: 'Consulta de facturación',
            deptId: 2,
            priorityId: 1,
            empresaId: 1,
            ticketNumber: '990011',
            body: 'Mensaje de prueba',
            statusId: 1
        );

        $arreglo = $dto->toArray();

        $this->assertEquals('990011', $arreglo['ticket_number']);
        $this->assertEquals(5, $arreglo['user_id']);
        $this->assertEquals('Consulta de facturación', $arreglo['subject']);
        $this->assertEquals(2, $arreglo['dept_id']);
        $this->assertEquals(1, $arreglo['priority_id']);
        $this->assertEquals(1, $arreglo['status_id']);
        $this->assertArrayHasKey('created', $arreglo);
    }

    public function test_ticket_repository_obtiene_estadisticas_y_paginacion_correctamente(): void
    {
        $repositorio = app(TicketRepository::class);

        $usuario = User::factory()->create();
        $ticket = $repositorio->crear([
            'ticket_number' => '554433',
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => 1,
            'subject' => 'Ticket para probar repositorio',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $paginados = $repositorio->obtenerPaginados(null, 10);
        $this->assertNotEmpty($paginados->items());

        $encontrado = $repositorio->buscarConDetalles($ticket->id);
        $this->assertNotNull($encontrado);
        $this->assertEquals('554433', $encontrado->ticket_number);

        $estadisticas = $repositorio->obtenerEstadisticas(1);
        $this->assertArrayHasKey('open', $estadisticas);
        $this->assertArrayHasKey('unassigned', $estadisticas);
        $this->assertArrayHasKey('mine', $estadisticas);
        $this->assertArrayHasKey('billing', $estadisticas);
    }
}
