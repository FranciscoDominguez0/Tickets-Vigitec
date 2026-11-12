@extends('emails.layouts.base')

@section('titulo', "Actualización de Ticket #{$ticket->ticket_number}")

@section('contenido')
    <!-- Saludo / Título -->
    <div style="margin-bottom: 20px;">
        <span class="badge badge-purple" style="margin-bottom: 10px;">Actualización de Estado</span>
        <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 8px 0 4px 0;">
            El ticket #{{ $ticket->ticket_number }} ha cambiado de estado
        </h2>
        <p style="color: #64748b; font-size: 14px; margin: 0;">
            Se ha registrado una transición en la atención de este requerimiento.
        </p>
    </div>

    <!-- Comparativa Visual de Estados -->
    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px 20px; margin: 20px 0; text-align: center;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 42%; text-align: center; vertical-align: middle;">
                    <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 6px;">Estado Anterior</div>
                    <span class="badge badge-blue" style="font-size: 12px; padding: 6px 14px;">
                        {{ $estadoAnterior }}
                    </span>
                </td>
                <td style="width: 16%; text-align: center; vertical-align: middle;">
                    <span style="font-size: 22px; color: #ef4444; font-weight: 800;">➔</span>
                </td>
                <td style="width: 42%; text-align: center; vertical-align: middle;">
                    <div style="font-size: 11px; text-transform: uppercase; color: #94a3b8; font-weight: 700; margin-bottom: 6px;">Nuevo Estado</div>
                    @php
                        $claseNuevo = match(strtolower($estadoNuevo)) {
                            'cerrado', 'resuelto' => 'badge-green',
                            'en espera', 'espera cliente' => 'badge-amber',
                            'en proceso', 'en camino' => 'badge-blue',
                            default => 'badge-purple',
                        };
                    @endphp
                    <span class="badge {{ $claseNuevo }}" style="font-size: 12px; padding: 6px 14px;">
                        {{ $estadoNuevo }}
                    </span>
                </td>
            </tr>
        </table>
        @if(!empty($nombreResponsable))
            <div style="margin-top: 14px; font-size: 12px; color: #64748b; border-top: 1px dashed #e2e8f0; padding-top: 10px;">
                Actualizado por: <strong style="color: #334155;">{{ $nombreResponsable }}</strong>
            </div>
        @endif
    </div>

    <!-- Ficha Resumen -->
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
            <span class="meta-label">Departamento:</span>
            <span class="meta-value">{{ $ticket->department->name ?? 'Soporte' }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Técnico Asignado:</span>
            <span class="meta-value">
                @if($ticket->staff)
                    {{ $ticket->staff->firstname }} {{ $ticket->staff->lastname }}
                @else
                    Sin asignar
                @endif
            </span>
        </div>
        <div class="meta-row">
            <span class="meta-label">Fecha y Hora:</span>
            <span class="meta-value">{{ now()->format('d/m/Y h:i A') }}</span>
        </div>
    </div>

    <!-- Botón de Acción -->
    <div style="text-align: center; margin: 30px 0 10px 0;">
        <a href="{{ $enlaceTicket }}" class="btn-cta" target="_blank">
            Revisar Ticket
        </a>
    </div>
@endsection

