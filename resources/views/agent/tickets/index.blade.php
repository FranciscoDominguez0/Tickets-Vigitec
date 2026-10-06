<x-agent.layout>
    <div class="tickets-shell">
        
        <!-- HEADER PREMIUM -->
        <div class="tickets-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <div>
                    <h1>Tickets</h1>
                    <div class="sub">
                        Abiertos: <strong>{{ $estadisticas['open'] }}</strong> · 
                        Sin asignar: <strong>{{ $estadisticas['unassigned'] }}</strong> · 
                        Míos: <strong>{{ $estadisticas['mine'] }}</strong> · 
                        Por facturar: <strong>{{ $estadisticas['billing'] }}</strong>
                    </div>
                </div>
                <a href="{{ route('agent.tickets.create') }}" class="btn-new"><i class="bi bi-plus-lg me-1"></i> Nuevo</a>
            </div>
        </div>

        <!-- FILTROS Y BÚSQUEDA -->
        <div class="tickets-toolbar">
            <div class="tickets-filters">
                <div class="dropdown filter-dd">
                    <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-funnel"></i> Abiertos
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item active" href="#">Abiertos</a></li>
                        <li><a class="dropdown-item" href="#">Sin asignar</a></li>
                        <li><a class="dropdown-item" href="#">Asignados a mí</a></li>
                        <li><a class="dropdown-item" href="#">Cerrados</a></li>
                        <li><a class="dropdown-item" href="#">Todos</a></li>
                    </ul>
                </div>

                <select class="form-select form-select-sm" aria-label="Filtrar por departamento" style="width: auto; min-width: 150px;">
                    <option value="0">Todos los deptos</option>
                    <option value="1">Soporte Técnico</option>
                </select>

                <div id="ticketDateRange" style="display:inline-flex; align-items:center; gap:6px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:4px 8px; height:32px;">
                    <i class="bi bi-calendar3" style="color:#64748b; font-size:0.8rem; flex-shrink:0;"></i>
                    <input type="date" id="dateFromInput" value="{{ date('Y-m-d', strtotime('-1 month')) }}" title="Desde" style="border:none; background:transparent; font-size:0.82rem; color:#334155; outline:none; width:110px; padding:0;">
                    <span style="color:#cbd5e1; font-size:0.75rem; flex-shrink:0;">—</span>
                    <input type="date" id="dateToInput" value="{{ date('Y-m-d') }}" title="Hasta" style="border:none; background:transparent; font-size:0.82rem; color:#334155; outline:none; width:110px; padding:0;">
                    <button type="button" id="applyDateRange" style="display:inline-flex; align-items:center; gap:3px; background:#ef4444; color:#fff; border:none; border-radius:6px; padding:2px 10px; font-size:0.78rem; font-weight:600; cursor:pointer; white-space:nowrap;">
                        <i class="bi bi-check-lg"></i> Aplicar
                    </button>
                </div>
            </div>
            
            <div class="tickets-search">
                <div class="input-group">
                    <span class="input-group-text bg-white" style="border-right: none; border-radius: 10px 0 0 10px;"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" style="border-left: none; border-radius: 0 10px 10px 0;" placeholder="Buscar ticket y presione Enter...">
                </div>
            </div>
        </div>

        <!-- TABLA -->
        <div class="tickets-table-wrap">
            <table class="table table-hover tickets-table mb-0">
                <thead class="table-light" style="border-bottom: 2px solid #e2e8f0; background-color: #f8fafc;">
                    <tr>
                        <th class="check-cell" style="width: 44px; text-align: center; vertical-align: middle;">
                            <input type="checkbox" class="form-check-input">
                        </th>
                        <th style="font-weight: 700; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; padding-left: 0;">Asunto del Ticket</th>
                        <th class="d-none d-lg-table-cell" style="font-weight: 700; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">Cliente</th>
                        <th class="d-none d-md-table-cell" style="font-weight: 700; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">Estado</th>
                        <th class="d-none d-lg-table-cell" style="font-weight: 700; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">Última Actividad</th>
                        <th style="width: 80px; text-align: right; font-weight: 700; color: #475569; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        @php
                            // Configuración visual según prioridad (muy simple)
                            $priorityName = strtolower($ticket->priority->name ?? 'baja');
                            $prioColor = match($priorityName) {
                                'alta', 'urgente' => '#f59e0b',
                                'normal' => '#10b981',
                                default => '#3b82f6'
                            };

                            // Configuración visual según estado
                            $statusName = strtolower($ticket->status->name ?? 'abierto');
                            $statusColor = match($statusName) {
                                'resuelto', 'cerrado' => '#22c55e',
                                default => '#3b82f6'
                            };
                        @endphp
                        <tr class="ticket-row" style="background: #fff; cursor: pointer; transition: all 0.2s;" onclick="window.location='{{ route('agent.tickets.show', $ticket->id) }}';">
                            <td class="check-cell" style="vertical-align: middle; text-align: center; width: 44px;">
                                <input class="form-check-input" type="checkbox" style="cursor: pointer; width: 1.1em; height: 1.1em;" onclick="event.stopPropagation();">
                            </td>
                            <td style="vertical-align: middle; padding: 18px 12px 18px 0;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                                    <a href="{{ route('agent.tickets.show', $ticket->id) }}" style="font-weight: 800; font-size: 1.05rem; color: #60a5fa; text-decoration: none;">
                                        <i class="bi bi-hash" style="opacity: 0.5;"></i>{{ $ticket->ticket_number }}
                                    </a>
                                    <span title="Prioridad: {{ $ticket->priority->name ?? 'Baja' }}" style="display:inline-flex; align-items:center; gap:4px; background:{{ $prioColor }}18; color:{{ $prioColor }}; border:1px solid {{ $prioColor }}40; border-radius:5px; padding:1px 7px; font-size:0.68rem; font-weight:800; letter-spacing:0.04em; line-height:1.6; text-transform:uppercase; white-space:nowrap;">
                                        <span style="width:5px; height:5px; border-radius:50%; background:{{ $prioColor }}; flex-shrink:0; display:inline-block;"></span>
                                        {{ $ticket->priority->name ?? 'Baja' }}
                                    </span>
                                </div>
                                <div style="font-weight: 600; color: #1e293b; font-size: 0.95rem; margin-bottom: 8px; line-height: 1.4; display: block; max-width: 55ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: none;">
                                    {{ $ticket->subject }}
                                </div>
                                <div style="display: flex; align-items: center; font-size: 0.8rem; color: #64748b;">
                                    <span style="display:inline-flex; align-items:center; gap:5px;">
                                        <i class="bi bi-headset" style="color:#94a3b8;"></i> Asignado a: <strong style="color: #475569; font-weight:600;">{{ $ticket->staff->firstname ?? 'Sin asignar' }}</strong>
                                    </span>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell" style="vertical-align: middle;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div style="display:flex; flex-direction:column;">
                                        <span style="font-weight: 700; color: #334155; font-size: 0.9rem;">{{ $ticket->user->firstname ?? 'N/A' }} {{ $ticket->user->lastname ?? '' }}</span>
                                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                            <i class="bi bi-building" style="font-size: 0.7rem;"></i> Empresa Predeterminada
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell" style="vertical-align: middle;">
                                <div style="display:flex; flex-direction:column; gap:6px; align-items: flex-start;">
                                    <span class="chip chip-status" style="background: {{ $statusColor }}15; color: {{ $statusColor }}; border: 1px solid {{ $statusColor }}33; padding: 6px 14px; font-weight: 700; letter-spacing: 0.03em; border-radius: 8px; font-size: 0.8rem; text-transform: uppercase;">
                                        <i class="bi bi-record-circle-fill" style="font-size: 0.6rem; margin-right: 4px; vertical-align: middle;"></i> {{ $ticket->status->name ?? 'ABIERTO' }}
                                    </span>
                                </div>
                            </td>
                            <td class="d-none d-lg-table-cell" style="vertical-align: middle;">
                                <div style="color:#64748b; font-size: 0.85rem; font-weight: 600; display:flex; align-items:center; gap:6px;">
                                    <i class="bi bi-clock-history" style="color:#94a3b8; font-size: 1rem;"></i>
                                    <span>{{ \Carbon\Carbon::parse($ticket->created)->format('d/m/Y h:i A') }}</span>
                                </div>
                            </td>
                            <td style="vertical-align: middle; text-align: right; padding-right: 12px;">
                                <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="btn btn-sm" style="background: transparent; color: #94a3b8; border: none; font-size: 1.2rem; transition: all 0.2s; display: inline-flex; align-items: center;" onmouseover="this.style.color='#60a5fa'" onmouseout="this.style.color='#94a3b8'">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                No se encontraron tickets.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tickets->hasPages())
        <div class="mt-3 p-3 bg-white rounded-3 shadow-sm border" style="border-color: #e2e8f0;">
            {{ $tickets->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</x-agent.layout>