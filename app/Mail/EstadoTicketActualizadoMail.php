<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstadoTicketActualizadoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Crea una nueva instancia de notificación de cambio de estado.
     */
    public function __construct(
        public Ticket $ticket,
        public string $estadoAnterior,
        public string $estadoNuevo,
        public ?string $nombreResponsable = null,
        public bool $esParaAgente = true,
    ) {
        $this->ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff']);
    }

    /**
     * Define el sobre del correo (Asunto).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Ticket #{$this->ticket->ticket_number}] Cambio de estado: {$this->estadoAnterior} ➔ {$this->estadoNuevo}",
        );
    }

    /**
     * Define el contenido del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets.estado_actualizado',
            with: [
                'ticket' => $this->ticket,
                'estadoAnterior' => $this->estadoAnterior,
                'estadoNuevo' => $this->estadoNuevo,
                'nombreResponsable' => $this->nombreResponsable,
                'esParaAgente' => $this->esParaAgente,
                'enlaceTicket' => $this->esParaAgente
                    ? route('agent.tickets.show', $this->ticket->id)
                    : route('client.tickets.show', $this->ticket->id),
            ],
        );
    }

    /**
     * Archivos adjuntos.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
