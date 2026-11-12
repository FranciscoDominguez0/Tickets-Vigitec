@extends('emails.layouts.base')

@section('titulo', "Nuevo Ticket #{$ticket->ticket_number}")

@section('contenido')
    <!-- Saludo / Título -->
    <div style="margin-bottom: 20px;">
        @if($esParaAgente)
            <span class="badge badge-red" style="margin-bottom: 10px;">Atención Agentes</span>
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 8px 0 4px 0;">
                Se ha registrado un nuevo ticket
            </h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">
                Un nuevo requerimiento de soporte ha ingresado al sistema y requiere tu revisión.
            </p>
        @else
            <span class="badge badge-green" style="margin-bottom: 10px;">Ticket Registrado</span>
            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 8px 0 4px 0;">
                Hemos recibido tu solicitud
            </h2>
            <p style="color: #64748b; font-size: 14px; margin: 0;">
                Hola <strong>{{ $ticket->user->name ?? 'Cliente' }}</strong>, tu ticket de soporte ha sido generado correctamente y nuestro equipo técnico lo atenderá a la brevedad.
            </p>
        @endif
    </div>

    <!-- Ficha Técnica del Ticket -->
    <div class="info-card">
        <div class="meta-row">
            <span class="meta-label">Número de Ticket:</span>
            <span class="meta-value" style="color: #ef4444; font-weight: 700;">#{{ $ticket->ticket_number }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Asunto:</span>
            <span class="meta-value" style="font-weight: 600;">{{ $ticket->subject }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Departamento:</span>
            <span class="meta-value">{{ $ticket->department->name ?? 'Soporte General' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Prioridad:</span>
            <span class="meta-value">
                @php
                    $prioridadNombre = strtolower($ticket->priority->name ?? 'normal');
                    $claseBadge = match (true) {
                        str_contains($prioridadNombre, 'alt') || str_contains($prioridadNombre, 'emerg') => 'badge-red',
                        str_contains($prioridadNombre, 'med') => 'badge-amber',
                        default => 'badge-blue',
                    };
                @endphp
                <span class="badge {{ $claseBadge }}">{{ $ticket->priority->name ?? 'Normal' }}</span>
            </span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Solicitante:</span>
            <span class="meta-value">{{ $ticket->user->name ?? 'Cliente' }} ({{ $ticket->user->email ?? 'N/A' }})</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Fecha de Ingreso:</span>
            <span class="meta-value">{{ $ticket->created ? \Carbon\Carbon::parse($ticket->created)->format('d/m/Y h:i A') : now()->format('d/m/Y h:i A') }}</span>
        </div>
    </div>

    <!-- Descripción Inicial -->
    @if(!empty($cuerpoInicial))
        <div style="margin: 25px 0;">
            <h3 style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                Descripción del Requerimiento:
            </h3>
            <div style="background-color: #f1f5f9; border-left: 4px solid #ef4444; padding: 14px 18px; border-radius: 4px; font-size: 13.5px; color: #334155; line-height: 1.5;">
                {!! nl2br(e(strip_tags($cuerpoInicial))) !!}
            </div>
        </div>
    @endif

    <!-- Botón de Acción -->
    <div style="text-align: center; margin: 30px 0 10px 0;">
        <a href="{{ $enlaceTicket }}" class="btn-cta" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Ver Ticket en el Panel
        </a>
    </div>
@endsection
