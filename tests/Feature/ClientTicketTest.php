<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Priority;
use App\Models\Thread;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Tests\TestCase;

class ClientTicketTest extends TestCase
{
    use DatabaseTransactions;

    protected Department $departamento;

    protected Priority $prioridad;

    protected TicketStatus $estadoAbierto;

    protected TicketStatus $estadoCerrado;

    protected function setUp(): void
    {
        parent::setUp();

        $this->departamento = Department::firstOrCreate(
            ['id' => 1, 'empresa_id' => 1],
            ['name' => 'Soporte Técnico', 'is_active' => 1]
        );

        $this->prioridad = Priority::firstOrCreate(
            ['id' => 1],
            ['name' => 'Baja', 'level' => 1, 'color' => '#3b82f6']
        );

        $this->estadoAbierto = TicketStatus::firstOrCreate(
            ['id' => 1],
            ['name' => 'Abierto', 'color' => '#3498db', 'icon' => 'fa-folder-open', 'order_by' => 1]
        );

        $this->estadoCerrado = TicketStatus::firstOrCreate(
            ['id' => 5],
            ['name' => 'Cerrado', 'color' => '#95a5a6', 'icon' => 'fa-times-circle', 'order_by' => 5]
        );
    }

    public function test_invitado_no_puede_acceder_al_dashboard_de_tickets(): void
    {
        $respuesta = $this->get('/dashboard');
        $respuesta->assertRedirect('/login');

        $respuestaCrear = $this->get('/tickets/create');
        $respuestaCrear->assertRedirect('/login');
    }

    public function test_cliente_autenticado_puede_ver_dashboard_con_sus_tickets(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);

        $ticket = Ticket::create([
            'ticket_number' => '998877',
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'status_id' => $this->estadoAbierto->id,
            'priority_id' => $this->prioridad->id,
            'subject' => 'Mi impresora no funciona',
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($usuario)->get('/dashboard');

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Mis Tickets');
        $respuesta->assertSee('998877');
        $respuesta->assertSee('Mi impresora no funciona');
    }

    public function test_cliente_no_ve_tickets_de_otros_usuarios_en_su_dashboard(): void
    {
        $usuarioA = User::factory()->create(['empresa_id' => 1]);
        $usuarioB = User::factory()->create(['empresa_id' => 1]);

        Ticket::create([
            'ticket_number' => '111222',
            'empresa_id' => 1,
            'user_id' => $usuarioB->id,
            'dept_id' => $this->departamento->id,
            'status_id' => $this->estadoAbierto->id,
            'priority_id' => $this->prioridad->id,
            'subject' => 'Ticket Privado de Usuario B',
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($usuarioA)->get('/dashboard');

        $respuesta->assertStatus(200);
        $respuesta->assertDontSee('Ticket Privado de Usuario B');
        $respuesta->assertDontSee('111222');
    }

    public function test_cliente_puede_ver_formulario_de_creacion_de_ticket(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);

        $respuesta = $this->actingAs($usuario)->get('/tickets/create');

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Nueva Solicitud de Soporte');
        $respuesta->assertSee($this->departamento->name);
    }

    public function test_cliente_puede_crear_un_ticket_exitosamente(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);

        $datos = [
            'dept_id' => $this->departamento->id,
            'subject' => 'Problema de red en la oficina',
            'body' => 'No hay acceso a internet desde las computadoras del piso 2.',
            'anydesk' => '987 654 321',
        ];

        $respuesta = $this->actingAs($usuario)->post('/tickets', $datos);

        $this->assertDatabaseHas('tickets', [
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'subject' => 'Problema de red en la oficina',
            'anydesk' => '987 654 321',
        ]);

        $ticket = Ticket::where('subject', 'Problema de red en la oficina')->first();
        $this->assertNotNull($ticket);

        $this->assertDatabaseHas('threads', [
            'ticket_id' => $ticket->id,
            'empresa_id' => 1,
        ]);

        $this->assertDatabaseHas('thread_entries', [
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'body' => 'No hay acceso a internet desde las computadoras del piso 2.',
            'is_internal' => 0,
        ]);

        $respuesta->assertRedirect(route('client.tickets.show', $ticket->id));
        $respuesta->assertSessionHas('success');
    }

    public function test_validacion_al_crear_ticket_sin_campos_obligatorios(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);

        $respuesta = $this->actingAs($usuario)->post('/tickets', []);

        $respuesta->assertSessionHasErrors(['dept_id', 'subject', 'body']);
    }

    public function test_cliente_puede_ver_detalle_de_su_ticket_y_mensajes(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);

        $ticket = Ticket::create([
            'ticket_number' => '445566',
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'status_id' => $this->estadoAbierto->id,
            'priority_id' => $this->prioridad->id,
            'subject' => 'Consulta de licencia',
            'created' => now(),
            'updated' => now(),
        ]);

        $hilo = Thread::create([
            'ticket_id' => $ticket->id,
            'empresa_id' => 1,
            'created' => now(),
        ]);

        ThreadEntry::create([
            'thread_id' => $hilo->id,
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'body' => 'Mensaje inicial de consulta.',
            'is_internal' => 0,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($usuario)->get("/tickets/{$ticket->id}");

        $respuesta->assertStatus(200);
        $respuesta->assertSee('445566');
        $respuesta->assertSee('Consulta de licencia');
        $respuesta->assertSee('Mensaje inicial de consulta.');
    }

    public function test_cliente_no_puede_ver_ticket_de_otro_usuario(): void
    {
        $usuarioA = User::factory()->create(['empresa_id' => 1]);
        $usuarioB = User::factory()->create(['empresa_id' => 1]);

        $ticketB = Ticket::create([
            'ticket_number' => '778899',
            'empresa_id' => 1,
            'user_id' => $usuarioB->id,
            'dept_id' => $this->departamento->id,
            'status_id' => $this->estadoAbierto->id,
            'priority_id' => $this->prioridad->id,
            'subject' => 'Ticket de Usuario B',
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($usuarioA)->get("/tickets/{$ticketB->id}");

        $respuesta->assertStatus(404);
    }

    public function test_cliente_puede_responder_a_su_ticket(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);

        $ticket = Ticket::create([
            'ticket_number' => '556677',
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'status_id' => $this->estadoAbierto->id,
            'priority_id' => $this->prioridad->id,
            'subject' => 'Ticket para responder',
            'created' => now(),
            'updated' => now(),
        ]);

        Thread::create([
            'ticket_id' => $ticket->id,
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $respuesta = $this->actingAs($usuario)->post("/tickets/{$ticket->id}/reply", [
            'body' => 'Esta es una respuesta adicional del cliente.',
        ]);

        $this->assertDatabaseHas('thread_entries', [
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'body' => 'Esta es una respuesta adicional del cliente.',
            'is_internal' => 0,
        ]);

        $respuesta->assertRedirect(route('client.tickets.show', $ticket->id));
        $respuesta->assertSessionHas('success');
    }

    public function test_busqueda_de_tickets_por_asunto_o_numero(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);

        Ticket::create([
            'ticket_number' => '123999',
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'status_id' => $this->estadoAbierto->id,
            'priority_id' => $this->prioridad->id,
            'subject' => 'Fallo urgente en servidor',
            'created' => now(),
            'updated' => now(),
        ]);

        Ticket::create([
            'ticket_number' => '456000',
            'empresa_id' => 1,
            'user_id' => $usuario->id,
            'dept_id' => $this->departamento->id,
            'status_id' => $this->estadoAbierto->id,
            'priority_id' => $this->prioridad->id,
            'subject' => 'Solicitud de mouse nuevo',
            'created' => now(),
            'updated' => now(),
        ]);

        $respuesta = $this->actingAs($usuario)->get('/dashboard?q=servidor');

        $respuesta->assertStatus(200);
        $respuesta->assertSee('Fallo urgente en servidor');
        $respuesta->assertDontSee('Solicitud de mouse nuevo');
    }

    public function test_maneja_excepcion_cuando_solicitud_supera_tamano_maximo(): void
    {
        $usuario = User::factory()->create(['empresa_id' => 1]);
        $this->actingAs($usuario);

        $solicitud = Request::create(route('client.tickets.store'), 'POST');
        $solicitud->setLaravelSession(session()->driver());

        $excepcion = new PostTooLargeException;
        $manejador = app(ExceptionHandler::class);
        $respuesta = $manejador->render($solicitud, $excepcion);

        $this->assertEquals(302, $respuesta->getStatusCode());
        $this->assertTrue(session()->has('error'));
        $this->assertStringContainsString('supera el límite permitido por el servidor', (string) session('error'));
    }
}
