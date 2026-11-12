<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketCreadoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Crea una nueva instancia de la notificación de ticket creado.
     */
    public function __construct(
        public Ticket $ticket,
        public ?string $cuerpoInicial = null,
        public bool $esParaAgente = true,
        public ?string $nombreRemitente = null,
    ) {
        $this->ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff']);
    }

    /**
     * Define el sobre del correo (Asunto y cabeceras).
     */
    public function envelope(): Envelope
    {
        $prefijo = $this->esParaAgente ? 'Nuevo ticket' : 'Confirmación de ticket';
        $asunto = "[Ticket #{$this->ticket->ticket_number}] {$prefijo}: {$this->ticket->subject}";

        return new Envelope(
            subject: $asunto,
        );
    }

    /**
     * Define el contenido del correo (Vista y parámetros).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets.creado',
            with: [
                'ticket' => $this->ticket,
                'cuerpoInicial' => $this->cuerpoInicial,
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
