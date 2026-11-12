<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Services\TicketPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TicketCerradoConFirmaMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Contenido binario del archivo PDF generado.
     */
    public string $pdfContenido;

    /**
     * Imagen de la firma codificada en Base64 para vista previa.
     */
    public ?string $firmaBase64 = null;

    /**
     * Crea una nueva instancia de la notificación de ticket cerrado con firma.
     */
    public function __construct(
        public Ticket $ticket,
        public bool $esParaAgente = true,
        public ?string $notasCierre = null,
        ?TicketPdfService $pdfService = null,
    ) {
        $this->ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff', 'report.items']);

        // Generar el PDF mediante el servicio especializado
        $servicio = $pdfService ?? app(TicketPdfService::class);
        $this->pdfContenido = $servicio->generarPdfActaCierre($this->ticket);

        // Preparar vista previa de la firma
        if (! empty($this->ticket->client_signature) && Storage::disk('public')->exists($this->ticket->client_signature)) {
            $archivoFirma = Storage::disk('public')->get($this->ticket->client_signature);
            $mime = Storage::disk('public')->mimeType($this->ticket->client_signature) ?? 'image/png';
            $this->firmaBase64 = 'data:'.$mime.';base64,'.base64_encode($archivoFirma);
        }
    }

    /**
     * Define el sobre del correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Ticket #{$this->ticket->ticket_number}] Ticket cerrado y firmado de conformidad",
        );
    }

    /**
     * Define el contenido del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tickets.cerrado_con_firma',
            with: [
                'ticket' => $this->ticket,
                'esParaAgente' => $this->esParaAgente,
                'notasCierre' => $this->notasCierre,
                'firmaBase64' => $this->firmaBase64,
                'enlaceTicket' => $this->esParaAgente
                    ? route('agent.tickets.show', $this->ticket->id)
                    : route('client.tickets.show', $this->ticket->id),
            ],
        );
    }

    /**
     * Adjunta el archivo PDF con la firma y acta de servicio.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $nombrePdf = 'acta_cierre_ticket_'.$this->ticket->ticket_number.'.pdf';

        return [
            Attachment::fromData(fn () => $this->pdfContenido, $nombrePdf)
                ->withMime('application/pdf'),
        ];
    }
}

