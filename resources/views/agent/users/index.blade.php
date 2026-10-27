<x-agent.layout>
    @push('styles')
    <style>
        .user-metric-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s, border-color 0.2s;
        }
        .user-metric-card:hover {
            border-color: rgba(239, 68, 68, 0.35);
            transform: translateY(-2px);
        }
        .user-metric-val {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }
        .user-metric-lbl {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }
        .user-metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
            flex-shrink: 0;
        }
        .user-filter-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        .user-table-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        .table-custom {
            width: 100%;
            color: #fff;
            margin-bottom: 0;
        }
        .table-custom th {
            background-color: #121417;
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            letter-spacing: 0.5px;
        }
        .table-custom td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            vertical-align: middle;
            color: rgba(255, 255, 255, 0.9);
        }
        .table-custom tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }
        .badge-status {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-active {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .badge-inactive {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }
        .ticket-counter-badge {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 6px;
            padding: 0.2rem 0.55rem;
            font-weight: 700;
            font-size: 0.75rem;
        }
        .btn-action-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-action-icon:hover {
            background: #ef4444;
            color: #fff;
            border-color: #ef4444;
        }
    </style>
    @endpush

    <div class="container-fluid px-0">
        <!-- Encabezado de página -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 text-white mb-1 fw-bold">Directorio de Usuarios</h1>
                <p class="text-muted mb-0 small">Gestión y consulta de clientes asociados a tickets de soporte</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-danger fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCrearUsuario" style="background-color: #ef4444; border-color: #ef4444; border-radius: 8px;">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Nuevo Usuario</span>
                </button>
            </div>
        </div>

        <!-- Alertas de sesión -->
        @if (session('exito'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4 border-0 rounded-3 shadow-sm" style="background: rgba(34, 197, 94, 0.15); color: #4ade80;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('exito') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 border-0 rounded-3 shadow-sm" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tarjetas de métricas -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="user-metric-card">
                    <div>
                        <div class="user-metric-val">{{ number_format($estadisticas['total']) }}</div>
                        <div class="user-metric-lbl">Total Clientes</div>
                    </div>
                    <div class="user-metric-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="user-metric-card">
                    <div>
                        <div class="user-metric-val text-success">{{ number_format($estadisticas['activos']) }}</div>
                        <div class="user-metric-lbl">Usuarios Activos</div>
                    </div>
                    <div class="user-metric-icon" style="background: rgba(34, 197, 94, 0.15); color: #4ade80;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="user-metric-card">
                    <div>
                        <div class="user-metric-val text-warning">{{ number_format($estadisticas['inactivos']) }}</div>
                        <div class="user-metric-lbl">Usuarios Inactivos</div>
                    </div>
                    <div class="user-metric-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de filtros y búsqueda -->
        <div class="user-filter-bar">
            <form action="{{ route('agent.users.directory') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control bg-dark text-white border-secondary" placeholder="Buscar por nombre, email, teléfono o empresa..." value="{{ $busqueda }}" style="border-color: rgba(255,255,255,0.1) !important;">
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="estado" class="form-select bg-dark text-white border-secondary" style="border-color: rgba(255,255,255,0.1) !important;">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ $estado === '1' ? 'selected' : '' }}>Activos</option>
                        <option value="0" {{ $estado === '0' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>
                <div class="col-6 col-md-4 col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-light px-3 flex-grow-1" style="border-radius: 8px;">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                    @if ($busqueda || $estado !== null)
                        <a href="{{ route('agent.users.directory') }}" class="btn btn-outline-secondary px-3" title="Limpiar filtros" style="border-radius: 8px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Tabla de usuarios -->
        <div class="user-table-card">
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Empresa / Organización</th>
                            <th class="text-center">Tickets</th>
                            <th>Estado</th>
                            <th>Registrado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $u)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="user-avatar">
                                            {{ $u->iniciales }}
                                        </div>
                                        <div>
                                            <a href="{{ route('agent.users.show', $u->id) }}" class="text-white text-decoration-none fw-bold d-block hover-danger">
                                                {{ $u->nombre_completo }}
                                            </a>
                                            <span class="text-muted small">ID: #{{ $u->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column small">
                                        <span class="text-white"><i class="bi bi-envelope me-1 text-muted"></i> {{ $u->email }}</span>
                                        @if ($u->phone)
                                            <span class="text-muted"><i class="bi bi-telephone me-1"></i> {{ $u->phone }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($u->company)
                                        <span class="text-light small"><i class="bi bi-building me-1 text-muted"></i> {{ $u->company }}</span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="ticket-counter-badge" title="Total tickets asociados">
                                        <i class="bi bi-ticket-perforated me-1"></i> {{ $u->total_tickets }}
                                    </span>
                                    @if ($u->tickets_abiertos > 0)
                                        <span class="badge bg-danger ms-1" title="Tickets abiertos o en proceso">
                                            {{ $u->tickets_abiertos }} abiertos
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($u->esActivo())
                                        <span class="badge-status badge-active">
                                            <i class="bi bi-dot fs-5"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge-status badge-inactive">
                                            <i class="bi bi-dot fs-5"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $u->created ? $u->created->format('d/m/Y') : '—' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('agent.users.show', $u->id) }}" class="btn-action-icon" title="Ver perfil y tickets">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('agent.users.edit', $u->id) }}" class="btn-action-icon" title="Editar datos">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('agent.users.toggle_status', $u->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action-icon" title="{{ $u->esActivo() ? 'Desactivar usuario' : 'Activar usuario' }}">
                                                <i class="bi {{ $u->esActivo() ? 'bi-toggle-on text-success' : 'bi-toggle-off text-muted' }}"></i>
                                            </button>
                                        </form>
                                        @if ($u->total_tickets == 0)
                                            <form action="{{ route('agent.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar a este usuario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-icon" title="Eliminar usuario">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-people d-block mb-2" style="font-size: 2.5rem; opacity: 0.5;"></i>
                                        <div class="fw-semibold">No se encontraron usuarios</div>
                                        <small>Intenta modificando los términos de búsqueda o filtros.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if ($usuarios->hasPages())
                <div class="p-3 border-top border-secondary" style="border-color: rgba(255,255,255,0.08) !important;">
                    {{ $usuarios->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Componente Reutilizable: Modal para Crear Usuario -->
    <x-modal-usuario />
</x-agent.layout>
