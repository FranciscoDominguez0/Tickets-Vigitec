<x-agent.layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-white mb-0">Tickets por facturar</h1>
    </div>

    <!-- Filtros -->
    <div class="card bg-dark border-secondary mb-4">
        <div class="card-body py-3">
            <form action="{{ route('agent.tickets.billing') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="text-white-50 small me-2">Mes:</label>
                </div>
                <div class="col-auto">
                    <select name="month" class="form-select form-select-sm bg-black text-white border-secondary" onchange="this.form.submit()">
                        <option value="all" {{ $month == 'all' ? 'selected' : '' }}>Todos</option>
                        @foreach($months as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($m)->translatedFormat('F Y') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto ms-auto">
                    <div class="input-group input-group-sm">
                        <input type="text" name="q" class="form-control bg-black text-white border-secondary" placeholder="Buscar ticket..." value="{{ $search }}">
                        <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card text-white" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-dark mb-0 align-middle">
                    <thead style="background: rgba(255,255,255,0.05);">
                        <tr>
                            <th class="ps-4">Ticket</th>
                            <th>Depto / Agente</th>
                            <th>Cliente</th>
                            <th>Estado Reporte</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $t)
                            @php
                                $hasReport = $t->report !== null;
                                $bstatus = $t->report->billing_status ?? 'pending';
                                $isFacturado = in_array($bstatus, ['confirmed', 'visita_tecnica', 'cotizacion']);
                                
                                $statusColor = $isFacturado || $hasReport ? '#10b981' : '#f59e0b'; // Green if done, orange if pending
                                $statusText = $bstatus !== 'pending' ? 'Facturado' : ($hasReport ? 'Completado' : 'Pendiente');
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">#{{ $t->ticket_number }}</div>
                                    <div class="small text-muted">{{ $t->closed ? \Carbon\Carbon::parse($t->closed)->format('d/m/Y h:i A') : '' }}</div>
                                    <div class="small text-truncate" style="max-width: 200px;" title="{{ $t->subject }}">{{ $t->subject }}</div>
                                </td>
                                <td>
                                    <div><i class="bi bi-building text-muted me-1"></i> {{ $t->department->name ?? 'N/D' }}</div>
                                    <div class="small text-muted"><i class="bi bi-headset me-1"></i> {{ $t->staff->firstname ?? '' }} {{ $t->staff->lastname ?? 'N/D' }}</div>
                                </td>
                                <td>
                                    <div><i class="bi bi-person text-muted me-1"></i> {{ $t->user->firstname ?? '' }} {{ $t->user->lastname ?? 'N/D' }}</div>
                                </td>
                                <td>
                                    <span style="color: {{ $statusColor }}; font-weight: 600;">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.6rem;"></i> {{ $statusText }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('agent.tickets.show', $t->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill me-1" title="Ver Ticket">
                                        <i class="bi bi-ticket-detailed"></i>
                                    </a>
                                    
                                    <a href="{{ route('agent.tickets.report_sheet', $t->id) }}" class="btn btn-sm rounded-pill fw-bold" 
                                       style="background: {{ $hasReport ? 'transparent' : 'linear-gradient(135deg, #ef4444, #991b1b)' }}; 
                                              border: {{ $hasReport ? '1px solid #3b82f6' : 'none' }}; 
                                              color: {{ $hasReport ? '#3b82f6' : '#fff' }};">
                                        <i class="bi {{ $hasReport ? 'bi-eye' : 'bi-plus-lg' }}"></i>
                                        {{ $hasReport ? 'Ver Reporte' : 'Reportar' }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                    No se encontraron tickets cerrados para el periodo seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center p-3 border-top border-secondary-subtle">
                {{ $tickets->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-agent.layout>