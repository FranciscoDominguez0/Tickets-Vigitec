<x-agent.layout>
    @push('styles')
    <style>
        .org-metric-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s, border-color 0.2s;
        }
        .org-metric-card:hover {
            border-color: rgba(239, 68, 68, 0.35);
            transform: translateY(-2px);
        }
        .org-metric-val {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }
        .org-metric-lbl {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }
        .org-metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .org-filter-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
        .org-card-box {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.2s ease-in-out;
            overflow: hidden;
        }
        .org-card-box:hover {
            border-color: rgba(239, 68, 68, 0.4);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
        .org-card-header-bar {
            padding: 1.25rem 1.25rem 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .org-avatar-badge {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }
        .org-title-link {
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            line-height: 1.2;
            transition: color 0.2s;
        }
        .org-title-link:hover {
            color: #ef4444;
        }
        .org-card-stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            padding: 0.75rem 1.25rem;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }
        .org-stat-item-num {
            font-size: 1.15rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
        }
        .org-stat-item-label {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.55);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 0.2rem;
        }
        .org-card-footer-bar {
            padding: 0.9rem 1.25rem;
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
        }
        .org-btn-details {
            color: #ef4444;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: gap 0.2s;
        }
        .org-btn-details:hover {
            color: #f87171;
            gap: 8px;
        }
    </style>
    @endpush

    <div class="container-fluid px-0">
        <!-- Encabezado de página -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 text-white mb-1 fw-bold">Organizaciones</h1>
                <p class="text-muted mb-0 small">Directorio de empresas y entidades asociadas a tickets y usuarios</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-danger fw-bold px-3 py-2 shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCrearOrganizacion" style="background-color: #ef4444; border-color: #ef4444; border-radius: 8px;">
                    <i class="bi bi-building-add"></i>
                    <span>Nueva Organización</span>
                </button>
            </div>
        </div>



        <!-- Tarjetas de métricas -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="org-metric-card">
                    <div>
                        <div class="org-metric-val">{{ number_format($estadisticas['total']) }}</div>
                        <div class="org-metric-lbl">Organizaciones</div>
                    </div>
                    <div class="org-metric-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="org-metric-card">
                    <div>
                        <div class="org-metric-val text-info">{{ number_format($estadisticas['miembros']) }}</div>
                        <div class="org-metric-lbl">Miembros Vinculados</div>
                    </div>
                    <div class="org-metric-icon" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="org-metric-card">
                    <div>
                        <div class="org-metric-val text-warning">{{ number_format($estadisticas['tickets_abiertos']) }}</div>
                        <div class="org-metric-lbl">Tickets Abiertos</div>
                    </div>
                    <div class="org-metric-icon" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="org-metric-card">
                    <div>
                        <div class="org-metric-val text-success">{{ number_format($estadisticas['tickets']) }}</div>
                        <div class="org-metric-lbl">Total Tickets</div>
                    </div>
                    <div class="org-metric-icon" style="background: rgba(34, 197, 94, 0.15); color: #4ade80;">
                        <i class="bi bi-ticket-perforated-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barra de filtros y búsqueda -->
        <div class="org-filter-bar">
            <form action="{{ route('agent.users.orgs') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="input-group">
                        <span class="input-group-text bg-dark border-secondary text-muted" style="border-color: rgba(255,255,255,0.1) !important;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control bg-dark text-white border-secondary" placeholder="Buscar organización por nombre, teléfono, sitio web o dirección..." value="{{ $busqueda }}" style="border-color: rgba(255,255,255,0.1) !important;">
                    </div>
                </div>
                <div class="col-6 col-md-2 col-lg-2">
                    <button type="submit" class="btn btn-outline-light w-100 fw-semibold" style="border-radius: 8px;">
                        <i class="bi bi-funnel me-1"></i> Filtrar
                    </button>
                </div>
                <div class="col-6 col-md-2 col-lg-1">
                    <a href="{{ route('agent.users.orgs') }}" class="btn btn-outline-secondary w-100" style="border-radius: 8px;" title="Limpiar búsqueda">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Cuadrícula de tarjetas de organizaciones -->
        @if ($organizaciones->isEmpty())
            <div class="text-center py-5 px-3 rounded-3" style="background-color: #16191d; border: 1px solid rgba(255,255,255,0.08);">
                <i class="bi bi-building d-block mb-3" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                <h4 class="text-white fw-bold">No se encontraron organizaciones</h4>
                <p class="text-muted small mb-3">
                    {{ $busqueda ? 'No hay resultados que coincidan con la búsqueda "' . $busqueda . '".' : 'Comienza registrando la primera organización en el sistema.' }}
                </p>
                <button type="button" class="btn btn-danger px-4 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCrearOrganizacion" style="border-radius: 8px;">
                    <i class="bi bi-plus-lg me-1"></i> Registrar Organización
                </button>
            </div>
        @else
            <div class="row g-3 mb-4">
                @foreach ($organizaciones as $org)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="org-card-box">
                            <div class="org-card-header-bar">
                                <div class="org-avatar-badge">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <a href="{{ route('agent.organizations.show', $org->id) }}" class="org-title-link text-truncate d-block" title="{{ $org->name }}">
                                        {{ $org->name }}
                                    </a>
                                    <span class="text-muted small text-truncate d-block">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $org->address ?: 'Sin dirección' }}
                                        @if ($org->phone)
                                            <span class="ms-2"><i class="bi bi-telephone me-1"></i>{{ $org->phone }}</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="org-card-stats-grid">
                                <div>
                                    <div class="org-stat-item-num text-info">{{ $org->total_usuarios }}</div>
                                    <div class="org-stat-item-label">Usuarios</div>
                                </div>
                                <div>
                                    <div class="org-stat-item-num text-warning">{{ $org->tickets_abiertos }}</div>
                                    <div class="org-stat-item-label">Abiertos</div>
                                </div>
                                <div>
                                    <div class="org-stat-item-num text-success">{{ $org->total_tickets }}</div>
                                    <div class="org-stat-item-label">Total</div>
                                </div>
                            </div>

                            <div class="org-card-footer-bar">
                                <span>
                                    <i class="bi bi-calendar-event me-1"></i>{{ $org->created ? $org->created->format('d/m/Y') : '—' }}
                                </span>
                                <a href="{{ route('agent.organizations.show', $org->id) }}" class="org-btn-details">
                                    <span>Ver detalles</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginación -->
            @if ($organizaciones->hasPages())
                <div class="p-3 border-top border-secondary" style="border-color: rgba(255,255,255,0.08) !important;">
                    {{ $organizaciones->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Modal Reutilizable para Crear Organización -->
    <x-modal 
        id="modalCrearOrganizacion"
        titulo="Registrar Nueva Organización"
        subtitulo="Crea una entidad o empresa para agrupar clientes y tickets"
        icono="bi-building"
        colorIcono="text-danger"
        :accion="route('agent.organizations.store')"
        metodo="POST"
        textoBoton="Guardar Organización"
        iconoBoton="bi-save"
        colorBoton="btn-danger"
        tamano="modal-lg"
    >
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nombre de la Organización <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Ej. Soluciones Globales S.A." required>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Teléfono Principal</label>
                <input type="text" name="phone" class="form-control" placeholder="+507 300-0000">
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Extensión Telefónica</label>
                <input type="text" name="phone_ext" class="form-control" placeholder="Ej. 101">
            </div>

            <div class="col-12">
                <label class="form-label">Sitio Web</label>
                <input type="url" name="website" class="form-control" placeholder="https://www.ejemplo.com">
            </div>

            <div class="col-12">
                <label class="form-label">Dirección Física</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Calle, edificio, piso, oficina..."></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Notas Internas</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Información interna relevante de la organización..."></textarea>
            </div>

            <div class="col-12">
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" type="checkbox" name="plain_text_emails" value="1" id="plainTextCheck">
                    <label class="form-check-label text-white small" for="plainTextCheck">
                        Enviar correos electrónicos solo en texto plano
                    </label>
                </div>
            </div>
        </div>
    </x-modal>
</x-agent.layout>