<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class TicketPdfService
{
    /**
     * Genera el contenido binario del PDF del acta de cierre de ticket con firma.
     */
    public function generarPdfActaCierre(Ticket $ticket): string
    {
        $datos = $this->prepararDatosParaPdf($ticket);

        $pdf = Pdf::loadView('pdf.ticket_acta_cierre', $datos);
        $pdf->setPaper('letter', 'portrait');

        return $pdf->output();
    }

    /**
     * Descarga el PDF del acta de cierre directamente en el navegador.
     */
    public function descargarPdfActaCierre(Ticket $ticket): Response
    {
        $datos = $this->prepararDatosParaPdf($ticket);

        $pdf = Pdf::loadView('pdf.ticket_acta_cierre', $datos);
        $pdf->setPaper('letter', 'portrait');

        $nombreArchivo = 'acta_cierre_ticket_'.$ticket->ticket_number.'.pdf';

        return $pdf->download($nombreArchivo);
    }

    /**
     * Prepara y estandariza los datos requeridos por la plantilla del PDF.
     *
     * @return array<string, mixed>
     */
    public function prepararDatosParaPdf(Ticket $ticket): array
    {
        $ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff', 'report.items', 'empresa']);

        $empresaId = (int) ($ticket->empresa_id ?? 1);

        $nombreEmpresa = (string) AppSetting::obtener('company.name', config('app.name', 'Sistema de Tickets'), $empresaId);
        $telefonoEmpresa = (string) AppSetting::obtener('company.phone', '', $empresaId);
        $direccionEmpresa = (string) AppSetting::obtener('company.address', '', $empresaId);
        $sitioWebEmpresa = (string) AppSetting::obtener('company.website', '', $empresaId);
        $logoRuta = (string) AppSetting::obtener('company.logo', '', $empresaId);

        // Convertir el logo a Base64 si existe en almacenamiento público
        $logoBase64 = null;
        if (! empty($logoRuta) && Storage::disk('public')->exists($logoRuta)) {
            $contenidoLogo = Storage::disk('public')->get($logoRuta);
            $mimeLogo = Storage::disk('public')->mimeType($logoRuta) ?? 'image/png';
            $logoBase64 = 'data:'.$mimeLogo.';base64,'.base64_encode($contenidoLogo);
        }

        // Convertir la firma del cliente a Base64 para garantizar su renderizado en DomPDF
        $firmaBase64 = null;
        if (! empty($ticket->client_signature) && Storage::disk('public')->exists($ticket->client_signature)) {
            $contenidoFirma = Storage::disk('public')->get($ticket->client_signature);
            $mimeFirma = Storage::disk('public')->mimeType($ticket->client_signature) ?? 'image/png';
            $firmaBase64 = 'data:'.$mimeFirma.';base64,'.base64_encode($contenidoFirma);
        }

        return [
            'ticket' => $ticket,
            'nombreEmpresa' => $nombreEmpresa,
            'telefonoEmpresa' => $telefonoEmpresa,
            'direccionEmpresa' => $direccionEmpresa,
            'sitioWebEmpresa' => $sitioWebEmpresa,
            'logoBase64' => $logoBase64,
            'firmaBase64' => $firmaBase64,
            'fechaGeneracion' => now()->format('d/m/Y h:i A'),
        ];
    }
}
