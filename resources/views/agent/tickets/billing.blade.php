<x-agent.layout>
    <!-- Header -->
    <div class="mb-4" style="background: radial-gradient(circle at 0% 0%, #ef4444 0%, #1a0000 35%, #000000 100%); border-radius: 14px; padding: 24px 22px; box-shadow: 0 8px 30px rgba(239, 68, 68, 0.2); position: relative; overflow: hidden;">
        <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 2;">
            <div>
                <h1 class="text-white mb-0" style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.01em;">Reportes de Costo</h1>
                <p class="mb-0 mt-1" style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">Tickets cerrados de departamentos que requieren reporte - {{ $tickets->total() }} resultados</p>
            </div>
            <div>
                <button type="button" class="btn btn-success rounded-3 px-4 py-2" style="background-color: #10b981; border: none; font-weight: 600; font-size: 0.9rem;">
                    <i class="bi bi-file-earmark-excel me-2"></i> Exportar Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
        <!-- Date Filter -->
        <form action="{{ route('agent.tickets.reports') }}" method="GET" class="d-flex align-items-center m-0" style="width: 260px;">
            @if($search)
                <input type="hidden" name="q" value="{{ $search }}">
            @endif
            <div class="input-group">
                <select name="month" class="form-select text-white shadow-none" style="background-color: #0b0f19; border: 1px solid rgba(255,255,255,0.1); border-right: none; border-radius: 8px 0 0 8px; font-size: 0.9rem;" onchange="this.form.submit()">
                    <option value="all" {{ $month == 'all' ? 'selected' : '' }}>Todos los meses</option>
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::parse($m)->translatedFormat('F Y') }}</option>
                    @endforeach
                </select>
                @if($month !== 'all')
                <button type="button" class="btn btn-outline-secondary" style="background-color: #0b0f19; border: 1px solid rgba(255,255,255,0.1); border-left: none; border-radius: 0 8px 8px 0;" onclick="window.location.href='{{ route('agent.tickets.reports', ['q' => $search]) }}'">
                    <i class="bi bi-x"></i>
                </button>
                @else
                <span class="input-group-text text-secondary" style="background-color: #0b0f19; border: 1px solid rgba(255,255,255,0.1); border-left: none; border-radius: 0 8px 8px 0;">
                    <i class="bi bi-calendar3"></i>
                </span>
                @endif
            </div>
        </form>
        
        <!-- Search Filter -->
        <form action="{{ route('agent.tickets.reports') }}" method="GET" class="m-0 flex-grow-1" style="max-width: 450px;">
            @if($month !== 'all')
                <input type="hidden" name="month" value="{{ $month }}">
            @endif
            <div class="input-group">
                <span class="input-group-text text-secondary" style="background-color: #0b0f19; border: 1px solid rgba(255,255,255,0.1); border-right: none; border-radius: 8px 0 0 8px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="q" class="form-control text-white shadow-none" style="background-color: #0b0f19; border: 1px solid rgba(255,255,255,0.1); border-left: none; border-radius: 0 8px 8px 0; font-size: 0.9rem;" placeholder="Buscar # ticket, depto, cliente..." value="{{ $search }}">
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card bg-transparent border-0" style="border: 1px solid rgba(255,255,255,0.05) !important; border-radius: 8px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-dark table-borderless mb-0 align-middle" style="background-color: #000;">
                <thead style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <tr>
                        <th class="py-3 px-4 text-muted" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 700;">TICKET</th>
                        <th class="py-3 px-4 text-muted" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 700;">DEPARTAMENTO</th>
                        <th class="py-3 px-4 text-muted" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 700;">ESTADO REPORTE</th>
                        <th class="py-3 px-4 text-muted" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 700;">FECHA CIERRE</th>
                        <th class="py-3 px-4 text-end text-muted" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 700;">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $t)
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td class="py-3 px-4">
                                <div class="fw-bold text-white mb-1">#{{ $t->ticket_number }}</div>
                                <div class="text-truncate" style="max-width: 250px; font-size: 0.85rem; color: rgba(255,255,255,0.6);" title="{{ $t->subject }}">{{ $t->subject }}</div>
                            </td>
                            <td class="py-3 px-4" style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">
                                {{ $t->department->name ?? 'N/D' }}
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    $hasReport = $t->report !== null;
                                @endphp
                                @if($hasReport)
                                    <span style="color: #10b981; font-weight: 600; font-size: 0.85rem;">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Facturado
                                    </span>
                                @else
                                    <span style="color: #f59e0b; font-weight: 600; font-size: 0.85rem;">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4" style="color: rgba(255,255,255,0.6); font-size: 0.9rem;">
                                {{ $t->closed ? \Carbon\Carbon::parse($t->closed)->format('d/m/Y h:i A') : '' }}
                            </td>
                            <td class="py-3 px-4 text-end">
                                @if(!$hasReport)
                                    <a href="{{ route('agent.tickets.report_sheet', $t->id) }}" class="btn btn-sm btn-danger px-3 py-1" style="font-weight: 500; font-size: 0.85rem;">
                                        Reportar
                                    </a>
                                @else
                                    <a href="{{ route('agent.tickets.report_sheet', $t->id) }}" class="btn btn-sm btn-outline-secondary px-3 py-1" style="border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.85rem;">
                                        Ver Reporte
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-inbox text-secondary d-block mb-3" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                <span class="text-secondary" style="font-size: 0.9rem; opacity: 0.7;">No hay tickets cerrados que requieran reporte.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
        <div class="d-flex justify-content-center p-3" style="background-color: #000; border-top: 1px solid rgba(255,255,255,0.05);">
            {{ $tickets->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</x-agent.layout>