<x-client.layout title="Ticket #{{ $ticket->ticket_number }}">
    <div class="row g-4">
        <!-- Columna Izquierda: Conversación y Respuestas -->
        <div class="col-lg-8">
            <!-- Navegación de migas de pan -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left me-1"></i> Mis Tickets
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Ticket #{{ $ticket->ticket_number }}</li>
                </ol>
            </nav>

            <!-- Encabezado del Ticket -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #ffffff;">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger text-white rounded-pill px-3 py-2 fw-bold" style="font-size: 0.85rem;">
                                #{{ $ticket->ticket_number }}
                            </span>
                            @php
                                $estadoNombre = strtolower($ticket->status->name ?? 'abierto');
                                $estadoColor = $ticket->status->color ?? match(true) {
                                    str_contains($estadoNombre, 'cerr') || str_contains($estadoNombre, 'resuel') => '#10b981',
                                    str_contains($estadoNombre, 'camino') || str_contains($estadoNombre, 'proceso') => '#f59e0b',
                                    default => '#3b82f6',
                                };
                            @endphp
                            <span class="badge rounded-pill px-3 py-2 fw-semibold" 
                                  style="background-color: {{ $estadoColor }}18; color: {{ $estadoColor }}; border: 1px solid {{ $estadoColor }}30;">
                                <span class="d-inline-block rounded-circle me-1" style="width: 6px; height: 6px; background-color: {{ $estadoColor }};"></span>
                                {{ $ticket->status->name ?? 'Abierto' }}
                            </span>
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i> {{ $ticket->created ? $ticket->created->format('d/m/Y H:i') : '' }}
                        </div>
                    </div>

                    <h1 class="h4 fw-bold text-dark mb-2">{{ $ticket->subject }}</h1>

                    <div class="d-flex flex-wrap gap-2 pt-2 border-top mt-3 text-muted small">
                        <span class="d-inline-flex align-items-center gap-1">
                            <i class="bi bi-building"></i> Departamento: <strong class="text-dark">{{ $ticket->department->name ?? 'General' }}</strong>
                        </span>
                        @if($ticket->helpTopic)
                            <span class="mx-1 text-secondary">•</span>
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="bi bi-tag"></i> Tema: <strong class="text-dark">{{ $ticket->helpTopic->name }}</strong>
                            </span>
                        @endif
                        @if($ticket->anydesk)
                            <span class="mx-1 text-secondary">•</span>
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="bi bi-display"></i> Anydesk: <code class="text-dark px-1 bg-light rounded">{{ $ticket->anydesk }}</code>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Hilo de Mensajes -->
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-3">Historial de Comunicación</h5>

                @php
                    $entradas = $ticket->thread ? $ticket->thread->entries : collect();
                @endphp

                @if($entradas->isEmpty())
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center text-muted" style="background: #ffffff;">
                        <i class="bi bi-chat-left-dots fs-3 mb-2 opacity-50"></i>
                        No hay mensajes registrados en este hilo todavía.
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($entradas as $entrada)
                            @php
                                $esStaff = !is_null($entrada->staff_id);
                                $autorNombre = $esStaff 
                                    ? ($entrada->staff ? trim($entrada->staff->firstname . ' ' . $entrada->staff->lastname) : 'Agente de Soporte') 
                                    : ($entrada->user ? trim($entrada->user->firstname . ' ' . $entrada->user->lastname) : 'Tú');
                                if ($autorNombre === '') {
                                    $autorNombre = $esStaff ? 'Agente de Soporte' : 'Tú';
                                }
                            @endphp
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden {{ $esStaff ? 'border-start border-4 border-primary' : '' }}" 
                                 style="background: {{ $esStaff ? '#f8fafc' : '#ffffff' }};">
                                <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between p-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold small" 
                                             style="width: 34px; height: 34px; background: {{ $esStaff ? 'linear-gradient(135deg, #3b82f6, #1d4ed8)' : 'linear-gradient(135deg, #ef4444, #f97316)' }}; font-size: 0.75rem;">
                                            {{ $esStaff ? 'SUP' : 'TU' }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block" style="font-size: 0.92rem;">
                                                {{ $autorNombre }}
                                                @if($esStaff)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-1" style="font-size: 0.7rem;">Soporte Técnico</span>
                                                @else
                                                    <span class="badge bg-light text-secondary border rounded-pill ms-1" style="font-size: 0.7rem;">Cliente</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-muted small">
                                        {{ $entrada->created ? \Carbon\Carbon::parse($entrada->created)->format('d/m/Y H:i') : '' }}
                                    </div>
                                </div>
                                <div class="card-body p-3 p-md-4">
                                    <div class="text-dark" style="white-space: pre-wrap; font-size: 0.95rem; line-height: 1.6;">{!! nl2br(e($entrada->body)) !!}</div>

                                    <!-- Adjuntos -->
                                    @if($entrada->attachments->isNotEmpty())
                                        <div class="mt-3 pt-3 border-top">
                                            <span class="text-muted small fw-semibold text-uppercase d-block mb-2">
                                                <i class="bi bi-paperclip me-1"></i> Archivos Adjuntos ({{ $entrada->attachments->count() }})
                                            </span>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($entrada->attachments as $adjunto)
                                                    <a href="{{ asset('storage/' . $adjunto->path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-light border rounded-pill px-3 py-1 text-decoration-none text-dark d-inline-flex align-items-center gap-2">
                                                        <i class="bi bi-file-earmark-arrow-down text-danger"></i>
                                                        <span class="small fw-semibold text-truncate" style="max-width: 180px;">{{ $adjunto->original_filename ?: $adjunto->filename }}</span>
                                                        @if($adjunto->size)
                                                            <span class="text-muted small">({{ round($adjunto->size / 1024, 1) }} KB)</span>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Formulario de Respuesta -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="card-header bg-white border-bottom p-3 p-md-4">
                    <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-reply-fill text-danger"></i> Agregar una Respuesta
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    @if(!is_null($ticket->closed))
                        <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center gap-3 mb-3 p-3">
                            <i class="bi bi-info-circle-fill fs-4 text-warning"></i>
                            <div class="small">
                                Este ticket se encuentra actualmente <strong>cerrado</strong>. Si envías una nueva respuesta, el caso será reabierto para que nuestro equipo lo revise.
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('client.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="body" class="form-label fw-semibold text-secondary small text-uppercase">Tu Mensaje <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('body') is-invalid @enderror" 
                                      id="body" 
                                      name="body" 
                                      rows="4" 
                                      placeholder="Escribe tu respuesta o consulta aquí..." 
                                      required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="attachments" class="form-label fw-semibold text-secondary small text-uppercase">
                                Adjuntar Archivos <span class="text-muted fw-normal">(Opcional)</span>
                            </label>
                            <input class="form-control form-control-sm @error('attachments.*') is-invalid @enderror" 
                                   type="file" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple>
                            @error('attachments.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end pt-2">
                            <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill fw-bold shadow-sm" style="background-color: #ef4444; border-color: #ef4444;">
                                <i class="bi bi-send me-1"></i> Enviar Respuesta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tarjeta de Información del Ticket -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="background: #ffffff; top: 90px;">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="fw-bold text-dark mb-0">Detalles del Ticket</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Número:</span>
                            <span class="fw-bold text-danger">#{{ $ticket->ticket_number }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Estado:</span>
                            <span class="badge rounded-pill px-2 py-1 fw-semibold" 
                                  style="background-color: {{ $estadoColor }}18; color: {{ $estadoColor }}; border: 1px solid {{ $estadoColor }}30;">
                                {{ $ticket->status->name ?? 'Abierto' }}
                            </span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Departamento:</span>
                            <span class="fw-semibold text-dark text-end">{{ $ticket->department->name ?? 'General' }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Agente Asignado:</span>
                            <span class="fw-semibold text-dark text-end">
                                @if($ticket->staff)
                                    {{ $ticket->staff->firstname }} {{ $ticket->staff->lastname }}
                                @else
                                    <span class="text-muted font-italic">En espera de asignación</span>
                                @endif
                            </span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Fecha Creación:</span>
                            <span class="fw-semibold text-dark small">{{ $ticket->created ? $ticket->created->format('d/m/Y H:i') : '-' }}</span>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <span class="text-muted small">Última Actividad:</span>
                            <span class="fw-semibold text-dark small">{{ $ticket->updated ? \Carbon\Carbon::parse($ticket->updated)->format('d/m/Y H:i') : '-' }}</span>
                        </li>
                    </ul>

                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary w-100 rounded-pill fw-semibold py-2">
                            <i class="bi bi-arrow-left me-1"></i> Volver a Mis Tickets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-client.layout>
