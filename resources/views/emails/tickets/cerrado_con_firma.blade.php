@extends('emails.layouts.base')

@section('titulo', "Ticket Cerrado y Firmado #{$ticket->ticket_number}")

@section('contenido')
    <!-- Banner de Cierre Exitoso -->
    <div style="margin-bottom: 20px;">
        <span class="badge badge-green" style="margin-bottom: 10px;">Ticket Cerrado y Resuelto</span>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 8px 0 4px 0;">
            Conformidad de Servicio Firmada
        </h2>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
            El ticket <strong>#{{ $ticket->ticket_number }}</strong> ha sido cerrado exitosamente con la firma de conformidad del cliente registrada en pantalla.
        </p>
    </div>

    <!-- Aviso de Archivo PDF Adjunto -->
    <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 14px 18px; margin: 20px 0; display: flex; align-items: center;">
        <div style="font-size: 13.5px; color: #1e40af; line-height: 1.45;">
            <strong>📎 Documento Adjunto:</strong> Hemos generado y adjuntado a este correo el <strong>Acta de Servicio y Cierre en formato PDF</strong> con el detalle completo del trabajo y la firma digital estampada.
        </div>
    </div>

    <!-- Resumen del Ticket -->
    <div class="info-card">
        <div class="meta-row">
            <span class="meta-label">Ticket:</span>
            <span class="meta-value">#{{ $ticket->ticket_number }} - {{ $ticket->subject }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Cliente:</span>
            <span class="meta-value">{{ $ticket->user->name ?? 'Cliente' }} ({{ $ticket->user->email ?? 'N/A' }})</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Técnico a Cargo:</span>
            <span class="meta-value">
                @if($ticket->staff)
                    {{ $ticket->staff->firstname }} {{ $ticket->staff->lastname }}
                @else
                    Personal Técnico
                @endif
            </span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Fecha de Apertura:</span>
            <span class="meta-value">{{ $ticket->created ? \Carbon\Carbon::parse($ticket->created)->format('d/m/Y h:i A') : 'N/A' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Fecha de Cierre:</span>
            <span class="meta-value" style="color: #16a34a; font-weight: 700;">
                {{ $ticket->closed ? \Carbon\Carbon::parse($ticket->closed)->format('d/m/Y h:i A') : now()->format('d/m/Y h:i A') }}
            </span>
        </div>
    </div>

    <!-- Vista Previa de la Firma Registrada -->
    @if($firmaBase64)
        <div style="margin: 25px 0; text-align: center;">
            <h3 style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                Firma Digital Registrada en Pantalla:
            </h3>
            <div style="background-color: #ffffff; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 12px; display: inline-block; max-width: 320px;">
                <img src="{{ $firmaBase64 }}" alt="Firma del Cliente" style="max-height: 85px; max-width: 100%;">
                <div style="font-size: 11px; color: #94a3b8; margin-top: 6px; border-top: 1px solid #f1f5f9; padding-top: 4px;">
                    Firmado por: <strong>{{ $ticket->user->name ?? 'Cliente' }}</strong>
                </div>
            </div>
        </div>
    @endif

    <!-- Botón de Acción -->
    <div style="text-align: center; margin: 25px 0 10px 0;">
        <a href="{{ $enlaceTicket }}" class="btn-cta" target="_blank">
            Ver Detalles del Ticket
        </a>
    </div>
@endsection

