<x-agent.admin-layout>
    @push('styles')
    <style>
        .deleted-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .deleted-hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .stat-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .control-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .deleted-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .deleted-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .deleted-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .deleted-table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            font-size: 0.88rem;
        }

        .deleted-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .badge-status-approved {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-status-rejected {
            background-color: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .badge-status-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
    </style>
    @endpush

    <!-- Header Hero -->
    <div class="deleted-hero">
        <div class="d-flex align-items-center gap-3">
            <div class="deleted-hero-icon">
                <i class="bi bi-trash-fill"></i>
            </div>
            <div>
                <h1 class="h4 text-white fw-bold mb-1">Historial de Tickets Borrados</h1>
                <p class="text-muted small mb-0">
                    Registro de auditoría con motivos de eliminación, respaldo completo, restauración y purga definitiva.
                </p>
            </div>
        </div>
    </div>

    <!-- Alertas Flash -->
    @if(session('success'))
        <div class="alert alert-success bg-dark text-success border border-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger bg-dark text-danger border border-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Tarjetas de Métricas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8;">
                    <i class="bi bi-archive-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">TOTAL REGISTRADOS</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                    <i class="bi bi-trash-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">ELIMINADOS</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['aprobados'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.15); color: #22c55e;">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">RESTAURADOS</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['restaurados'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">PENDIENTES</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['pendientes'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="control-bar">
        <form method="GET" action="{{ route('admin.deleted_tickets') }}" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-black border-secondary text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" value="{{ $busqueda }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Buscar por número, asunto o motivo de eliminación...">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-black border-secondary text-muted">Estado:</span>
                    <select name="estado" class="form-select bg-black text-white border-secondary shadow-none" onchange="this.form.submit()">
                        <option value="todos" {{ $filtroEstado === 'todos' ? 'selected' : '' }}>Todos los estados</option>
                        <option value="approved" {{ $filtroEstado === 'approved' ? 'selected' : '' }}>Eliminados / Archivados</option>
                        <option value="rejected" {{ $filtroEstado === 'rejected' ? 'selected' : '' }}>Restaurados</option>
                        <option value="pending" {{ $filtroEstado === 'pending' ? 'selected' : '' }}>Pendientes de resolución</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-light w-100">
                    Filtrar
                </button>
                @if(!empty($busqueda) || $filtroEstado !== 'todos')
                    <a href="{{ route('admin.deleted_tickets') }}" class="btn btn-sm btn-dark text-muted" title="Limpiar filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabla de Registros -->
    <div class="deleted-table-wrapper mb-4">
        <div class="table-responsive">
            <table class="deleted-table">
                <thead>
                    <tr>
                        <th style="width: 140px;">Ticket</th>
                        <th>Asunto</th>
                        <th>Motivo del Borrado</th>
                        <th style="width: 170px;">Solicitante</th>
                        <th style="width: 120px;">Estado</th>
                        <th style="width: 150px;">Fecha</th>
                        <th style="width: 140px;" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($registros as $registro)
                        <tr>
                            <td>
                                <span class="badge rounded-pill bg-dark border border-secondary text-white font-monospace px-2 py-1">
                                    #{{ $registro->ticket_number }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-white text-truncate" style="max-width: 250px;" title="{{ $registro->ticket_subject }}">
                                    {{ $registro->ticket_subject }}
                                </div>
                            </td>
                            <td>
                                <div class="text-muted text-truncate" style="max-width: 280px;" title="{{ $registro->reason }}">
                                    <i class="bi bi-chat-quote me-1 opacity-75"></i> {{ $registro->reason }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white small" style="width: 26px; height: 26px; font-size: 0.75rem;">
                                        {{ strtoupper(substr($registro->solicitante->firstname ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="small text-white">
                                        {{ $registro->solicitante ? $registro->solicitante->firstname . ' ' . $registro->solicitante->lastname : 'Agente' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @if($registro->status === 'rejected')
                                    <span class="badge rounded-pill badge-status-rejected px-2 py-1 small">
                                        <i class="bi bi-check-circle me-1"></i> Restaurado
                                    </span>
                                @elseif($registro->status === 'approved')
                                    <span class="badge rounded-pill badge-status-approved px-2 py-1 small">
                                        <i class="bi bi-trash me-1"></i> Eliminado
                                    </span>
                                @else
                                    <span class="badge rounded-pill badge-status-pending px-2 py-1 small">
                                        <i class="bi bi-clock me-1"></i> Pendiente
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted">
                                    {{ $registro->created_at ? $registro->created_at->format('d/m/Y h:i A') : 'N/A' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- Ver Detalle -->
                                    <button type="button" class="btn btn-action btn-outline-info" data-bs-toggle="modal" data-bs-target="#detalleModal{{ $registro->id }}" title="Ver detalles y auditoría">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <!-- Restaurar (si está eliminado) -->
                                    @if($registro->status !== 'rejected')
                                        <button type="button" class="btn btn-action btn-outline-success" data-bs-toggle="modal" data-bs-target="#restaurarModal{{ $registro->id }}" title="Restaurar ticket al sistema">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>
                                    @endif

                                    <!-- Purgar Registro -->
                                    <button type="button" class="btn btn-action btn-outline-danger" data-bs-toggle="modal" data-bs-target="#purgarModal{{ $registro->id }}" title="Purgar definitivamente este registro">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>

                                <!-- Modal Detalle -->
                                <x-agent.modal id="detalleModal{{ $registro->id }}" title="Detalles del Ticket Borrado #{{ $registro->ticket_number }}" size="modal-lg">
                                    <div class="row g-3 text-start">
                                        <div class="col-md-6">
                                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Número de Ticket</label>
                                            <div class="text-white fs-5 fw-bold">#{{ $registro->ticket_number }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Fecha de Eliminación</label>
                                            <div class="text-white">{{ $registro->created_at ? $registro->created_at->format('d/m/Y h:i:s A') : 'N/A' }}</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Asunto</label>
                                            <div class="p-2 rounded bg-black border border-secondary text-white">{{ $registro->ticket_subject }}</div>
                                        </div>
                                        <div class="col-12">
                                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Motivo Registrado por el Agente</label>
                                            <div class="p-3 rounded bg-black border border-danger text-light" style="font-size: 0.95rem;">
                                                <i class="bi bi-quote text-danger fs-5 me-1"></i>
                                                {{ $registro->reason }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Agente que Eliminó</label>
                                            <div class="text-white">{{ $registro->solicitante ? $registro->solicitante->firstname . ' ' . $registro->solicitante->lastname : 'N/A' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">Estado en Auditoría</label>
                                            <div>
                                                @if($registro->status === 'rejected')
                                                    <span class="badge badge-status-rejected">Restaurado</span>
                                                @elseif($registro->status === 'approved')
                                                    <span class="badge badge-status-approved">Eliminado Definitivo</span>
                                                @else
                                                    <span class="badge badge-status-pending">Pendiente</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!empty($registro->ticket_payload))
                                            <div class="col-12 mt-3">
                                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">Datos Respaldados del Solicitante</label>
                                                <div class="small p-2 rounded bg-dark border border-secondary text-muted">
                                                    <div><strong>Cliente:</strong> {{ $registro->ticket_payload['user_name'] ?? 'N/A' }} ({{ $registro->ticket_payload['user_email'] ?? 'N/A' }})</div>
                                                    <div><strong>Departamento:</strong> {{ $registro->ticket_payload['dept_name'] ?? 'N/A' }}</div>
                                                    <div><strong>Mensajes en el hilo respaldados:</strong> {{ count($registro->ticket_payload['thread_entries'] ?? []) }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <x-slot:footer>
                                        <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                                    </x-slot:footer>
                                </x-agent.modal>

                                <!-- Modal Restaurar -->
                                @if($registro->status !== 'rejected')
                                    <div class="modal fade" id="restaurarModal{{ $registro->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-dark text-white border-success">
                                                <div class="modal-header border-secondary">
                                                    <h5 class="modal-title fw-bold text-success d-flex align-items-center gap-2">
                                                        <i class="bi bi-arrow-counterclockwise fs-5"></i> Restaurar Ticket #{{ $registro->ticket_number }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.deleted_tickets.restore', $registro->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body text-start">
                                                        <p class="text-muted mb-3">
                                                            ¿Deseas restaurar este ticket? Se reincorporará con todos sus datos originales y mensajes de hilo a la bandeja principal de tickets.
                                                        </p>
                                                        <div class="p-2 rounded bg-black border border-secondary small text-muted">
                                                            <strong>Asunto:</strong> {{ $registro->ticket_subject }}<br>
                                                            <strong>Motivo del borrado previo:</strong> {{ $registro->reason }}
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-secondary">
                                                        <button type="button" class="btn btn-dark border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-success fw-bold">
                                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Sí, Restaurar Ticket
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Modal Purgar -->
                                <x-delete-modal 
                                    id="purgarModal{{ $registro->id }}" 
                                    title="Purgar Registro #{{ $registro->ticket_number }}" 
                                    message="¿Estás seguro de que deseas purgar definitivamente este registro de auditoría? Esta acción borrará el respaldo y no se podrá restaurar el ticket."
                                    formAction="{{ route('admin.deleted_tickets.purge', $registro->id) }}"
                                    submitText="Purgar Definitivamente"
                                />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                @if(!empty($busqueda) || $filtroEstado !== 'todos')
                                    No se encontraron tickets borrados que coincidan con los filtros aplicados.
                                @else
                                    No hay tickets borrados registrados en el historial de auditoría.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($registros->hasPages())
            <div class="p-3 border-top border-secondary border-opacity-25 d-flex justify-content-center">
                {{ $registros->links() }}
            </div>
        @endif
    </div>
</x-agent.admin-layout>

