<x-agent.admin-layout>
    @push('styles')
    <style>
        .notifications-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .notifications-hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(245, 158, 11, 0.25);
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

        .notifications-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .notifications-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .notifications-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            white-space: nowrap;
        }

        .notifications-table td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.88rem;
            color: #e2e8f0;
        }

        .notifications-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .notifications-table tr.fila-no-leida td {
            background-color: rgba(245, 158, 11, 0.04);
        }

        .pill-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .batch-bar {
            background: linear-gradient(90deg, #1e293b, #0f172a);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            margin-bottom: 1rem;
            display: none;
        }
    </style>
    @endpush

    <!-- Alertas Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 mb-4 shadow" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0 mb-4 shadow" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0 mb-4 shadow" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Cabecera Hero -->
    <div class="notifications-hero d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="notifications-hero-icon">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1">Notificaciones y Alertas del Sistema</h4>
                <p class="text-muted small mb-0">Gestiona los avisos del personal, notificaciones de clientes y destinatarios de alertas globales.</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Botón Marcar Todo Leído -->
            <form action="{{ route('admin.notifications.read_all', ['audiencia' => $filtroAudiencia]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-2 fw-semibold px-3 py-2" onclick="return confirm('¿Deseas marcar todas las notificaciones pendientes como leídas?');">
                    <i class="bi bi-check2-all fs-6"></i>
                    <span>Marcar Todo Leído</span>
                </button>
            </form>

            <!-- Botón Enviar Notificación -->
            <button type="button" class="btn btn-warning btn-sm d-flex align-items-center gap-2 fw-semibold px-3 py-2 text-dark" data-bs-toggle="modal" data-bs-target="#enviarNotificacionModal">
                <i class="bi bi-send-fill fs-6"></i>
                <span>Enviar Notificación</span>
            </button>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(14, 165, 233, 0.15); color: #0ea5e9;">
                    <i class="bi bi-bell"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['total']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Total Emitidas</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                    <i class="bi bi-envelope-exclamation"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['no_leidas']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">No Leídas</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.15); color: #a855f7;">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['alertas_personal']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Personal (Staff)</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['alertas_clientes']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Clientes</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas Principales -->
    <ul class="nav nav-pills mb-3 gap-2" id="notificacionesTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $pestañaActiva === 'historial' ? 'active bg-warning text-dark fw-bold' : 'text-white bg-dark border border-secondary' }}" 
               href="{{ route('admin.notifications', ['tab' => 'historial', 'audiencia' => $filtroAudiencia]) }}">
                <i class="bi bi-clock-history me-1"></i> Historial de Notificaciones
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $pestañaActiva === 'destinatarios' ? 'active bg-warning text-dark fw-bold' : 'text-white bg-dark border border-secondary' }}" 
               href="{{ route('admin.notifications', ['tab' => 'destinatarios']) }}">
                <i class="bi bi-person-gear me-1"></i> Destinatarios de Alertas Globales
            </a>
        </li>
    </ul>

    @if($pestañaActiva === 'historial')
        <!-- Sub-pestañas de Audiencia (Personal vs Clientes) -->
        <div class="d-flex align-items-center gap-2 mb-3">
            <a href="{{ route('admin.notifications', ['tab' => 'historial', 'audiencia' => 'personal']) }}" 
               class="btn btn-sm {{ $filtroAudiencia === 'personal' ? 'btn-primary fw-bold' : 'btn-dark text-muted border border-secondary' }}">
                <i class="bi bi-person-badge me-1"></i> Personal del Sistema ({{ $estadisticas['alertas_personal'] }})
            </a>
            <a href="{{ route('admin.notifications', ['tab' => 'historial', 'audiencia' => 'clientes']) }}" 
               class="btn btn-sm {{ $filtroAudiencia === 'clientes' ? 'btn-primary fw-bold' : 'btn-dark text-muted border border-secondary' }}">
                <i class="bi bi-people me-1"></i> Clientes / Usuarios ({{ $estadisticas['alertas_clientes'] }})
            </a>
        </div>

        <!-- Barra de Control y Filtros -->
        <div class="control-bar">
            <form action="{{ route('admin.notifications') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="tab" value="historial">
                <input type="hidden" name="audiencia" value="{{ $filtroAudiencia }}">
                
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-black border-secondary text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" value="{{ $busqueda }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Buscar por mensaje, tipo o destinatario...">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="tipo" class="form-select form-select-sm bg-black text-white border-secondary shadow-none">
                        <option value="todos" {{ $filtroTipo === 'todos' ? 'selected' : '' }}>Todos los Tipos</option>
                        <option value="general" {{ $filtroTipo === 'general' ? 'selected' : '' }}>General</option>
                        <option value="alerta" {{ $filtroTipo === 'alerta' ? 'selected' : '' }}>Alerta / Crítico</option>
                        <option value="mantenimiento" {{ $filtroTipo === 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                        <option value="recordatorio" {{ $filtroTipo === 'recordatorio' ? 'selected' : '' }}>Recordatorio</option>
                        <option value="sistema" {{ $filtroTipo === 'sistema' ? 'selected' : '' }}>Sistema</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="estado" class="form-select form-select-sm bg-black text-white border-secondary shadow-none">
                        <option value="todos" {{ $filtroEstado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>
                        <option value="no_leidas" {{ $filtroEstado === 'no_leidas' ? 'selected' : '' }}>Solo No Leídas</option>
                        <option value="leidas" {{ $filtroEstado === 'leidas' ? 'selected' : '' }}>Solo Leídas</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-warning btn-sm text-dark fw-bold w-100">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                    @if(!empty($busqueda) || $filtroTipo !== 'todos' || $filtroEstado !== 'todos')
                        <a href="{{ route('admin.notifications', ['tab' => 'historial', 'audiencia' => $filtroAudiencia]) }}" class="btn btn-dark btn-sm border-secondary text-muted">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Barra flotante de acciones masivas -->
        <div class="batch-bar" id="batchBar">
            <form action="{{ route('admin.notifications.mass') }}" method="POST" id="formAccionMasiva" class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                @csrf
                <input type="hidden" name="audiencia" value="{{ $filtroAudiencia }}">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-warning text-dark fw-bold" id="contadorSeleccionados">0</span>
                    <span class="text-white small">notificaciones seleccionadas</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <select name="accion" class="form-select form-select-sm bg-black text-white border-secondary shadow-none" style="width: auto;" required>
                        <option value="">Acción por lotes...</option>
                        <option value="marcar_leido">Marcar como leídas</option>
                        <option value="marcar_no_leido">Marcar como no leídas</option>
                        <option value="eliminar">Eliminar seleccionadas</option>
                    </select>
                    <button type="submit" class="btn btn-warning btn-sm text-dark fw-semibold px-3" onclick="return confirm('¿Deseas aplicar esta acción a las notificaciones seleccionadas?');">
                        Aplicar
                    </button>
                </div>
                <div id="contenedorIdsOcultos"></div>
            </form>
        </div>

        @if($filtroAudiencia === 'personal')
            <!-- Tabla de Notificaciones del Personal -->
            <div class="notifications-table-wrapper">
                <table class="notifications-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="checkTodo" class="form-check-input bg-black border-secondary">
                            </th>
                            <th style="width: 100px;">Estado</th>
                            <th style="width: 130px;">Tipo</th>
                            <th style="width: 220px;">Destinatario</th>
                            <th>Mensaje</th>
                            <th style="width: 150px;">Fecha</th>
                            <th style="width: 110px;" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notificacionesPersonal as $notif)
                            <tr class="{{ !$notif->is_read ? 'fila-no-leida' : '' }}">
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input item-check bg-black border-secondary" value="{{ $notif->id }}">
                                </td>
                                <td>
                                    @if(!$notif->is_read)
                                        <span class="pill-badge bg-warning text-dark fw-bold">
                                            <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> No leída
                                        </span>
                                    @else
                                        <span class="pill-badge bg-secondary text-white">
                                            <i class="bi bi-check2"></i> Leída
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($notif->type === 'alerta')
                                        <span class="badge bg-danger text-white px-2 py-1"><i class="bi bi-exclamation-triangle me-1"></i> Alerta</span>
                                    @elseif($notif->type === 'mantenimiento')
                                        <span class="badge bg-warning text-dark px-2 py-1"><i class="bi bi-tools me-1"></i> Mantenimiento</span>
                                    @elseif($notif->type === 'recordatorio')
                                        <span class="badge bg-info text-white px-2 py-1"><i class="bi bi-alarm me-1"></i> Recordatorio</span>
                                    @elseif($notif->type === 'sistema')
                                        <span class="badge bg-primary text-white px-2 py-1"><i class="bi bi-gear me-1"></i> Sistema</span>
                                    @else
                                        <span class="badge bg-dark border border-secondary text-light px-2 py-1">General</span>
                                    @endif
                                </td>
                                <td>
                                    @if($notif->staff)
                                        <div class="fw-semibold text-white">{{ $notif->staff->firstname }} {{ $notif->staff->lastname }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $notif->staff->email }}</div>
                                    @else
                                        <span class="text-muted fst-italic">Personal no disponible (ID: {{ $notif->staff_id }})</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-white">{{ $notif->message }}</div>
                                    @if($notif->related_id)
                                        <div class="mt-1">
                                            <a href="{{ route('agent.ticket.view', $notif->related_id) }}" class="badge bg-dark border border-info text-info text-decoration-none">
                                                <i class="bi bi-ticket-detailed me-1"></i> Ticket #{{ $notif->related_id }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-white small">{{ $notif->created_at ? $notif->created_at->format('d/m/Y H:i') : '-' }}</div>
                                    <div class="text-muted" style="font-size: 0.73rem;">{{ $notif->created_at ? $notif->created_at->diffForHumans() : '' }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        @if(!$notif->is_read)
                                            <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="audiencia" value="personal">
                                                <button type="submit" class="btn btn-sm btn-outline-warning p-1 px-2" title="Marcar como leída">
                                                    <i class="bi bi-check2"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-danger p-1 px-2" data-bs-toggle="modal" data-bs-target="#eliminarNotifPersonalModal{{ $notif->id }}" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Eliminar Notificación Personal -->
                            <x-delete-modal 
                                id="eliminarNotifPersonalModal{{ $notif->id }}" 
                                title="Eliminar Notificación" 
                                message="¿Estás seguro de que deseas eliminar esta notificación del historial del personal?"
                                formAction="{{ route('admin.notifications.destroy', ['id' => $notif->id, 'audiencia' => 'personal']) }}"
                                submitText="Eliminar"
                            />
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                                    No se encontraron notificaciones del personal con los filtros aplicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $notificacionesPersonal->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Tabla de Notificaciones de Clientes -->
            <div class="notifications-table-wrapper">
                <table class="notifications-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="checkTodo" class="form-check-input bg-black border-secondary">
                            </th>
                            <th style="width: 100px;">Estado</th>
                            <th style="width: 130px;">Tipo</th>
                            <th style="width: 220px;">Cliente / Usuario</th>
                            <th>Mensaje</th>
                            <th style="width: 150px;">Fecha</th>
                            <th style="width: 110px;" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notificacionesClientes as $notifCliente)
                            <tr class="{{ !$notifCliente->is_read ? 'fila-no-leida' : '' }}">
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input item-check bg-black border-secondary" value="{{ $notifCliente->id }}">
                                </td>
                                <td>
                                    @if(!$notifCliente->is_read)
                                        <span class="pill-badge bg-warning text-dark fw-bold">
                                            <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> No leída
                                        </span>
                                    @else
                                        <span class="pill-badge bg-secondary text-white">
                                            <i class="bi bi-check2"></i> Leída
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-dark border border-secondary text-light px-2 py-1">
                                        {{ ucfirst($notifCliente->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($notifCliente->user)
                                        <div class="fw-semibold text-white">{{ $notifCliente->user->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $notifCliente->user->email }}</div>
                                    @else
                                        <span class="text-muted fst-italic">Usuario ID: {{ $notifCliente->user_id }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-white">{{ $notifCliente->message }}</div>
                                    @if($notifCliente->ticket_id)
                                        <div class="mt-1">
                                            <a href="{{ route('agent.ticket.view', $notifCliente->ticket_id) }}" class="badge bg-dark border border-info text-info text-decoration-none">
                                                <i class="bi bi-ticket-detailed me-1"></i> Ticket #{{ $notifCliente->ticket_id }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-white small">{{ $notifCliente->created_at ? $notifCliente->created_at->format('d/m/Y H:i') : '-' }}</div>
                                    <div class="text-muted" style="font-size: 0.73rem;">{{ $notifCliente->created_at ? $notifCliente->created_at->diffForHumans() : '' }}</div>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        @if(!$notifCliente->is_read)
                                            <form action="{{ route('admin.notifications.read', $notifCliente->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="audiencia" value="clientes">
                                                <button type="submit" class="btn btn-sm btn-outline-warning p-1 px-2" title="Marcar como leída">
                                                    <i class="bi bi-check2"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-danger p-1 px-2" data-bs-toggle="modal" data-bs-target="#eliminarNotifClienteModal{{ $notifCliente->id }}" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Eliminar Notificación Cliente -->
                            <x-delete-modal 
                                id="eliminarNotifClienteModal{{ $notifCliente->id }}" 
                                title="Eliminar Notificación" 
                                message="¿Estás seguro de que deseas eliminar esta notificación del historial de clientes?"
                                formAction="{{ route('admin.notifications.destroy', ['id' => $notifCliente->id, 'audiencia' => 'clientes']) }}"
                                submitText="Eliminar"
                            />
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary"></i>
                                    No se encontraron notificaciones a clientes con los filtros aplicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $notificacionesClientes->appends(request()->query())->links() }}
            </div>
        @endif

    @else
        <!-- Pestaña de Destinatarios de Alertas Globales -->
        <div class="card bg-black border border-secondary text-white p-4 rounded-3 shadow">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-2 fs-4">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Destinatarios de Alertas Globales del Sistema</h5>
                    <p class="text-muted small mb-0">Selecciona qué miembros del personal recibirán avisos automáticos de eventos globales (ej. tickets nuevos sin asignar, cierres, alertas críticas).</p>
                </div>
            </div>

            <form action="{{ route('admin.notifications.recipients') }}" method="POST">
                @csrf
                <div class="row g-3 my-2">
                    @forelse($agentes as $agente)
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 rounded bg-dark border border-secondary d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-weight: 600;">
                                        {{ strtoupper(substr($agente->firstname, 0, 1)) }}{{ strtoupper(substr($agente->lastname, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-white small">{{ $agente->firstname }} {{ $agente->lastname }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">{{ $agente->email }}</div>
                                        <span class="badge {{ $agente->isadmin ? 'bg-danger' : 'bg-secondary' }}" style="font-size: 0.65rem;">
                                            {{ $agente->isadmin ? 'Administrador' : 'Agente' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" name="staff_ids[]" value="{{ $agente->id }}" id="checkAgente{{ $agente->id }}" {{ in_array($agente->id, $destinatariosGlobales, true) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">
                            No hay agentes registrados en la empresa.
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 pt-3 border-top border-secondary d-flex justify-content-end">
                    <button type="submit" class="btn btn-warning fw-bold px-4 text-dark">
                        <i class="bi bi-save me-1"></i> Guardar Destinatarios
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Modal Enviar Notificación -->
    <x-agent.modal id="enviarNotificacionModal" title="Emitir Notificación Administrativa" size="modal-lg">
        <form action="{{ route('admin.notifications.send') }}" method="POST" id="formEnviarNotificacion">
            @csrf
            <div class="row g-3 text-start">
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Destinatarios <span class="text-danger">*</span></label>
                    <select name="destinatario_tipo" id="selectorDestinatario" class="form-select bg-black text-white border-secondary shadow-none" required onchange="toggleSelectoresDestinatario(this.value)">
                        <option value="todos_agentes">Todos los Agentes (Staff)</option>
                        <option value="agente_especifico">Agente Específico</option>
                        <option value="todos_clientes">Todos los Clientes (Usuarios)</option>
                        <option value="cliente_especifico">Cliente Específico</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Tipo de Notificación <span class="text-danger">*</span></label>
                    <select name="type" class="form-select bg-black text-white border-secondary shadow-none" required>
                        <option value="general">General</option>
                        <option value="alerta">Alerta Crítica</option>
                        <option value="mantenimiento">Aviso de Mantenimiento</option>
                        <option value="recordatorio">Recordatorio</option>
                        <option value="sistema">Sistema</option>
                    </select>
                </div>

                <!-- Selector de Agente Específico -->
                <div class="col-md-12 d-none" id="bloqueAgenteEspecifico">
                    <label class="form-label text-white small fw-bold">Seleccionar Agente <span class="text-danger">*</span></label>
                    <select name="staff_id" class="form-select bg-black text-white border-secondary shadow-none">
                        <option value="">Selecciona un agente...</option>
                        @foreach($agentes as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->firstname }} {{ $ag->lastname }} ({{ $ag->email }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Selector de Cliente Específico -->
                <div class="col-md-12 d-none" id="bloqueClienteEspecifico">
                    <label class="form-label text-white small fw-bold">Seleccionar Cliente <span class="text-danger">*</span></label>
                    <select name="user_id" class="form-select bg-black text-white border-secondary shadow-none">
                        <option value="">Selecciona un cliente...</option>
                        @foreach($clientes as $cl)
                            <option value="{{ $cl->id }}">{{ $cl->name }} ({{ $cl->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label text-white small fw-bold">ID de Ticket Relacionado (Opcional)</label>
                    <input type="number" name="related_id" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Ej: 15 (si la notificación es relativa a un ticket)">
                </div>

                <div class="col-12">
                    <label class="form-label text-white small fw-bold">Mensaje de la Notificación <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control bg-black text-white border-secondary shadow-none" rows="4" placeholder="Escribe el contenido del aviso o alerta..." required maxlength="1000"></textarea>
                    <div class="form-text text-muted small">Máximo 1000 caracteres.</div>
                </div>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formEnviarNotificacion" class="btn btn-warning fw-bold px-4 text-dark">
                <i class="bi bi-send-fill me-1"></i> Emitir Notificación
            </button>
        </x-slot:footer>
    </x-agent.modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkTodo = document.getElementById('checkTodo');
            const itemsCheck = document.querySelectorAll('.item-check');
            const batchBar = document.getElementById('batchBar');
            const contadorSeleccionados = document.getElementById('contadorSeleccionados');
            const contenedorIdsOcultos = document.getElementById('contenedorIdsOcultos');

            function actualizarBarraLotes() {
                const seleccionados = Array.from(itemsCheck).filter(c => c.checked);
                if (seleccionados.length > 0) {
                    batchBar.style.display = 'flex';
                    contadorSeleccionados.textContent = seleccionados.length;
                    
                    contenedorIdsOcultos.innerHTML = '';
                    seleccionados.forEach(c => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = c.value;
                        contenedorIdsOcultos.appendChild(input);
                    });
                } else {
                    batchBar.style.display = 'none';
                    contenedorIdsOcultos.innerHTML = '';
                }
            }

            if (checkTodo) {
                checkTodo.addEventListener('change', function () {
                    itemsCheck.forEach(c => c.checked = checkTodo.checked);
                    actualizarBarraLotes();
                });
            }

            itemsCheck.forEach(c => {
                c.addEventListener('change', function () {
                    actualizarBarraLotes();
                    if (!this.checked && checkTodo) {
                        checkTodo.checked = false;
                    }
                });
            });
        });

        function toggleSelectoresDestinatario(valor) {
            const bloqueAgente = document.getElementById('bloqueAgenteEspecifico');
            const bloqueCliente = document.getElementById('bloqueClienteEspecifico');

            bloqueAgente.classList.add('d-none');
            bloqueCliente.classList.add('d-none');

            if (valor === 'agente_especifico') {
                bloqueAgente.classList.remove('d-none');
            } else if (valor === 'cliente_especifico') {
                bloqueCliente.classList.remove('d-none');
            }
        }
    </script>
    @endpush
</x-agent.admin-layout>

