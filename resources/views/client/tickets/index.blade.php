<x-client.layout title="Mis Tickets">
    <div class="row align-items-center justify-content-between mb-4">
        <div class="col-md-7">
            <h1 class="h3 fw-bold mb-1 text-dark">Mis Tickets</h1>
            <p class="text-muted mb-0">Gestiona tus solicitudes de soporte técnico y consulta el progreso de atención.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <a href="{{ route('client.tickets.create') }}" class="btn btn-danger px-4 py-2 fw-bold rounded-pill shadow-sm" style="background-color: #ef4444; border-color: #ef4444;">
                <i class="bi bi-plus-circle me-1"></i> Abrir Nuevo Ticket
            </a>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas / KPI -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #ffffff;">
                <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold small text-uppercase">Tickets Abiertos</span>
                        <h2 class="fw-bold mt-1 mb-0" style="color: #ef4444;">{{ $estadisticas['abiertos'] ?? 0 }}</h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #fef2f2; color: #ef4444;">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #ffffff;">
                <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold small text-uppercase">Tickets Cerrados</span>
                        <h2 class="fw-bold mt-1 mb-0" style="color: #10b981;">{{ $estadisticas['cerrados'] ?? 0 }}</h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #ecfdf5; color: #10b981;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #ffffff;">
                <div class="card-body p-3 p-md-4 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-semibold small text-uppercase">Total Histórico</span>
                        <h2 class="fw-bold mt-1 mb-0 text-dark">{{ $estadisticas['total'] ?? 0 }}</h2>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background-color: #f1f5f9; color: #64748b;">
                        <i class="bi bi-inboxes fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenedor Principal de la Lista -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff;">
        <!-- Pestañas y Buscador -->
        <div class="card-header bg-white border-bottom p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-md-6">
                    <ul class="nav nav-pills gap-1">
                        <li class="nav-item">
                            <a class="nav-link rounded-pill fw-semibold px-3 py-1 {{ $filtro === 'abiertos' ? 'active bg-danger text-white' : 'text-secondary' }}" 
                               href="{{ route('dashboard', array_merge(request()->query(), ['filtro' => 'abiertos'])) }}">
                                Abiertos <span class="badge rounded-pill ms-1 {{ $filtro === 'abiertos' ? 'bg-white text-danger' : 'bg-light text-dark' }}">{{ $estadisticas['abiertos'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill fw-semibold px-3 py-1 {{ $filtro === 'cerrados' ? 'active bg-danger text-white' : 'text-secondary' }}" 
                               href="{{ route('dashboard', array_merge(request()->query(), ['filtro' => 'cerrados'])) }}">
                                Cerrados <span class="badge rounded-pill ms-1 {{ $filtro === 'cerrados' ? 'bg-white text-danger' : 'bg-light text-dark' }}">{{ $estadisticas['cerrados'] }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link rounded-pill fw-semibold px-3 py-1 {{ $filtro === 'todos' ? 'active bg-danger text-white' : 'text-secondary' }}" 
                               href="{{ route('dashboard', array_merge(request()->query(), ['filtro' => 'todos'])) }}">
                                Todos <span class="badge rounded-pill ms-1 {{ $filtro === 'todos' ? 'bg-white text-danger' : 'bg-light text-dark' }}">{{ $estadisticas['total'] }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('dashboard') }}" class="d-flex">
                        <input type="hidden" name="filtro" value="{{ $filtro }}">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-pill ps-3 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" value="{{ $busqueda }}" class="form-control bg-light border-start-0 border-end-0 py-2" placeholder="Buscar por número o asunto...">
                            @if($busqueda !== '')
                                <a href="{{ route('dashboard', ['filtro' => $filtro]) }}" class="btn btn-light border-start-0 border-end-0 text-muted" title="Limpiar búsqueda">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                            <button class="btn btn-dark rounded-end-pill px-4 fw-semibold" type="submit">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabla de Tickets -->
        <div class="card-body p-0">
            @if($tickets->isEmpty())
                <div class="text-center py-5 px-3">
                    <div class="mb-3 text-muted">
                        <i class="bi bi-inbox" style="font-size: 3.5rem; opacity: 0.4;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No se encontraron tickets</h5>
                    <p class="text-muted mx-auto" style="max-width: 420px;">
                        @if($busqueda !== '')
                            No hay resultados que coincidan con "{{ $busqueda }}". Intenta con otra búsqueda o limpia el filtro.
                        @else
                            No tienes solicitudes registradas bajo la pestaña seleccionada.
                        @endif
                    </p>
                    <a href="{{ route('client.tickets.create') }}" class="btn btn-outline-danger rounded-pill fw-semibold px-4 mt-2">
                        <i class="bi bi-plus-lg me-1"></i> Abrir un nuevo ticket
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fw-bold text-secondary" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                            <tr>
                                <th class="ps-4 py-3" style="width: 140px;">Número</th>
                                <th class="py-3">Asunto</th>
                                <th class="py-3 d-none d-md-table-cell">Departamento</th>
                                <th class="py-3 text-center">Estado</th>
                                <th class="py-3 d-none d-lg-table-cell">Fecha</th>
                                <th class="pe-4 py-3 text-end" style="width: 120px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($tickets as $ticket)
                                @php
                                    $estadoNombre = strtolower($ticket->status->name ?? 'abierto');
                                    $estadoColor = $ticket->status->color ?? match(true) {
                                        str_contains($estadoNombre, 'cerr') || str_contains($estadoNombre, 'resuel') => '#10b981',
                                        str_contains($estadoNombre, 'camino') || str_contains($estadoNombre, 'proceso') => '#f59e0b',
                                        default => '#3b82f6',
                                    };
                                    $esCerrado = !is_null($ticket->closed);
                                @endphp
                                <tr style="cursor: pointer;" onclick="window.location='{{ route('client.tickets.show', $ticket->id) }}';">
                                    <td class="ps-4 py-3">
                                        <a href="{{ route('client.tickets.show', $ticket->id) }}" class="fw-bold text-decoration-none" style="color: #ef4444;">
                                            #{{ $ticket->ticket_number }}
                                        </a>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 380px;">
                                            {{ $ticket->subject }}
                                        </div>
                                        @if($ticket->helpTopic)
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-tag me-1"></i>{{ $ticket->helpTopic->name }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="py-3 d-none d-md-table-cell text-muted">
                                        <span class="d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-building small"></i>
                                            {{ $ticket->department->name ?? 'General' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge rounded-pill px-3 py-2 fw-semibold" 
                                              style="background-color: {{ $estadoColor }}18; color: {{ $estadoColor }}; border: 1px solid {{ $estadoColor }}30;">
                                            <span class="d-inline-block rounded-circle me-1" style="width: 6px; height: 6px; background-color: {{ $estadoColor }};"></span>
                                            {{ $ticket->status->name ?? ($esCerrado ? 'Cerrado' : 'Abierto') }}
                                        </span>
                                    </td>
                                    <td class="py-3 d-none d-lg-table-cell text-muted small">
                                        {{ $ticket->created ? $ticket->created->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="pe-4 py-3 text-end">
                                        <a href="{{ route('client.tickets.show', $ticket->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 fw-semibold text-secondary" onclick="event.stopPropagation();">
                                            Ver <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                @if($tickets->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $tickets->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-client.layout>

