<?php

namespace Tests\Feature;

use App\Mail\EstadoTicketActualizadoMail;
use App\Mail\TicketCerradoConFirmaMail;
use App\Mail\TicketCreadoMail;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Services\TicketPdfService;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketMailAndPdfTest extends TestCase
{
    use DatabaseTransactions;

    protected Department $departamento;

    protected Priority $prioridad;

    protected TicketStatus $estadoAbierto;

    protected TicketStatus $estadoEnProceso;

    protected TicketStatus $estadoCerrado;

    protected Staff $agente;

    protected User $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->departamento = Department::firstOrCreate(['id' => 1], [
            'name' => 'Soporte Técnico',
            'empresa_id' => 1,
            'is_active' => 1,
        ]);

        $this->prioridad = Priority::firstOrCreate(['id' => 1], [
            'name' => 'Alta',
            'level' => 1,
            'color' => '#e74c3c',
        ]);

        $this->estadoAbierto = TicketStatus::firstOrCreate(['id' => 1], [
            'name' => 'Abierto',
            'color' => '#3498db',
            'order_by' => 1,
        ]);

        $this->estadoEnProceso = TicketStatus::firstOrCreate(['id' => 3], [
            'name' => 'En proceso',
            'color' => '#f39c12',
            'order_by' => 3,
        ]);

        $this->estadoCerrado = TicketStatus::firstOrCreate(['id' => 5], [
            'name' => 'Cerrado',
            'color' => '#2ecc71',
            'order_by' => 5,
        ]);

        $this->agente = Staff::factory()->create([
            'email' => 'tecnico.soporte@example.com',
            'empresa_id' => 1,
            'dept_id' => $this->departamento->id,
            'is_active' => 1,
        ]);

        $this->cliente = User::factory()->create([
            'email' => 'cliente.solicitante@example.com',
            'empresa_id' => 1,
        ]);
    }

    /**
     * Verifica que al crear un ticket se envíe el correo profesional tanto al agente como al cliente.
     */
    public function test_envia_correo_al_crear_ticket_a_agente_y_cliente(): void
    {
        Mail::fake();

        $ticketService = app(TicketService::class);

        $ticket = $ticketService->crearTicketCliente(
            usuarioId: $this->cliente->id,
            empresaId: 1,
            departamentoId: $this->departamento->id,
            asunto: 'Error en conexión del servidor',
            cuerpo: 'No puedo conectarme a la base de datos principal desde la sucursal.'
        );

        $this->assertNotNull($ticket);

        // Verificar que el correo se envió al agente del departamento y al cliente
        Mail::assertSent(TicketCreadoMail::class, function ($mail) {
            return $mail->hasTo('tecnico.soporte@example.com')
                && $mail->esParaAgente === true
                && str_contains($mail->envelope()->subject, 'Error en conexión del servidor');
        });

        Mail::assertSent(TicketCreadoMail::class, function ($mail) {
            return $mail->hasTo('cliente.solicitante@example.com')
                && $mail->esParaAgente === false;
        });
    }

    /**
     * Verifica que al actualizar el estado de un ticket se notifique a las partes interesadas.
     */
    public function test_envia_correo_al_cambiar_estado_del_ticket(): void
    {
        Mail::fake();

        $ticket = Ticket::create([
            'ticket_number' => 'TCK-2026-001',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estadoAbierto->id,
            'staff_id' => $this->agente->id,
            'subject' => 'Mantenimiento de servidor',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        $ticketService = app(TicketService::class);
        $ticketService->actualizarEstado($ticket, $this->estadoEnProceso->id, $this->agente->id);

        Mail::assertSent(EstadoTicketActualizadoMail::class, function ($mail) {
            return $mail->hasTo('tecnico.soporte@example.com')
                && $mail->estadoAnterior === 'Abierto'
                && $mail->estadoNuevo === 'En proceso';
        });

        Mail::assertSent(EstadoTicketActualizadoMail::class, function ($mail) {
            return $mail->hasTo('cliente.solicitante@example.com')
                && $mail->estadoNuevo === 'En proceso';
        });
    }

    /**
     * Verifica que el servicio PDF genere un documento PDF válido que comience con cabecera %PDF.
     */
    public function test_servicio_pdf_genera_acta_de_cierre_valida(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-PDF-999',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estadoCerrado->id,
            'staff_id' => $this->agente->id,
            'subject' => 'Instalación de fibra óptica',
            'empresa_id' => 1,
            'created' => now(),
            'closed' => now(),
        ]);

        $servicioPdf = app(TicketPdfService::class);
        $pdfContenido = $servicioPdf->generarPdfActaCierre($ticket);

        $this->assertNotEmpty($pdfContenido);
        $this->assertStringStartsWith('%PDF', $pdfContenido);
    }

    /**
     * Verifica que el cierre de un ticket con firma digital envíe el correo con el PDF adjunto.
     */
    public function test_cierre_con_firma_envia_correo_con_pdf_adjunto(): void
    {
        Mail::fake();

        $ticket = Ticket::create([
            'ticket_number' => 'TCK-SIGN-777',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estadoAbierto->id,
            'staff_id' => $this->agente->id,
            'subject' => 'Soporte presencial en sitio',
            'empresa_id' => 1,
            'created' => now(),
        ]);

        // Simular firma digital en Base64 (PNG 1x1 transparente)
        $firmaBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $respuesta = $this->actingAs($this->agente, 'staff')
            ->post("/agent/ticket/{$ticket->id}/request-signature", [
                'firma_base64' => $firmaBase64,
            ]);

        $respuesta->assertRedirect();

        $ticket->refresh();
        $this->assertEquals(5, $ticket->status_id);
        $this->assertNotNull($ticket->client_signature);
        $this->assertNotNull($ticket->closed);

        // Comprobar envío del correo con acta en PDF adjunta
        Mail::assertSent(TicketCerradoConFirmaMail::class, function ($mail) use ($ticket) {
            $tienePdfAdjunto = collect($mail->attachments())->contains(function ($attachment) use ($ticket) {
                return str_contains($attachment->as, $ticket->ticket_number)
                    && $attachment->mime === 'application/pdf';
            });

            return $mail->hasTo('tecnico.soporte@example.com') && $tienePdfAdjunto;
        });

        Mail::assertSent(TicketCerradoConFirmaMail::class, function ($mail) {
            return $mail->hasTo('cliente.solicitante@example.com');
        });
    }

    /**
     * Verifica que un agente pueda descargar directamente el PDF del acta desde su ruta correspondiente.
     */
    public function test_agente_puede_descargar_el_pdf_del_ticket(): void
    {
        $ticket = Ticket::create([
            'ticket_number' => 'TCK-DOWNLOAD-111',
            'user_id' => $this->cliente->id,
            'dept_id' => $this->departamento->id,
            'priority_id' => $this->prioridad->id,
            'status_id' => $this->estadoCerrado->id,
            'staff_id' => $this->agente->id,
            'subject' => 'Reparación de hardware',
            'empresa_id' => 1,
            'created' => now(),
            'closed' => now(),
        ]);

        $respuesta = $this->actingAs($this->agente, 'staff')
            ->get("/agent/ticket/{$ticket->id}/pdf");

        $respuesta->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $respuesta->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $respuesta->headers->get('Content-Disposition'));
        $this->assertStringContainsString('TCK-DOWNLOAD-111', $respuesta->headers->get('Content-Disposition'));
    }
}
