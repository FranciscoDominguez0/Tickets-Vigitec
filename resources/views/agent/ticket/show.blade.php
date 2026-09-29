<x-agent.layout>
    <!-- Encabezado del ticket -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-white mb-0">
            Ticket #{{ $ticket->ticket_number ?? '000000' }} - {{ $ticket->subject ?? 'Sin asunto' }}
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('agent.tickets.index') ?? '#' }}" class="btn btn-outline-light btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Principal: Conversación y Detalles del Usuario -->
        <div class="col-md-8">
            <div class="card text-white mb-4" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body">
                    <p class="text-muted mb-0">Creado por: <strong class="text-light">{{ $ticket->user->firstname ?? 'Usuario' }} {{ $ticket->user->lastname ?? '' }}</strong></p>
                    <hr class="border-secondary">
                    
                    <!-- Espacio reservado para los mensajes de la conversación -->
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-dots d-block mb-3" style="font-size: 3rem;"></i>
                        No hay mensajes para mostrar.
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Secundaria: Sidebar de Detalles del Ticket -->
        <div class="col-md-4">
            <div class="card text-white text-md-end" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body p-4">
                    @php
                        // Determinar si el ticket está cerrado para ajustar el color
                        $isClosed = ($ticket->status_id ?? 0) === 5;
                        $statusColor = $isClosed ? '#e74c3c' : '#3498db';
                    @endphp

                    <!-- Estado -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Estado</small>
                        <span class="badge" style="background-color: {{ $statusColor }}; color: white; font-size: 0.95rem;">
                            {{ $ticket->status->name ?? 'Abierto' }}
                        </span>
                    </div>

                    <!-- Prioridad -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Prioridad</small>
                        <span class="badge" style="background-color: #f39c12; color: white; font-size: 0.95rem;">
                            {{ $ticket->priority->name ?? 'Normal' }}
                        </span>
                    </div>

                    <!-- Departamento -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Departamento</small>
                        <span class="text-light">
                            {{ $ticket->department->name ?? 'General' }}
                        </span>
                    </div>

                    <!-- Agente Asignado -->
                    <div>
                        <small class="text-muted d-block mb-1">Asignado a</small>
                        <span class="text-light">
                            @if(isset($ticket->staff))
                                {{ $ticket->staff->firstname }} {{ $ticket->staff->lastname }}
                            @else
                                Sin asignar
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="mt-3 d-grid gap-2">
                @if(isset($isClosed) && $isClosed)
                    <button type="button" class="btn" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; font-weight: 600; border: none;">
                        <i class="bi bi-arrow-counterclockwise"></i> Reabrir Ticket
                    </button>
                @else
                    <button type="button" class="btn" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; font-weight: 600; border: none;">
                        <i class="bi bi-check2-circle"></i> Cerrar Ticket
                    </button>
                @endif
            </div>
        </div>
    </div>
</x-agent.layout>
