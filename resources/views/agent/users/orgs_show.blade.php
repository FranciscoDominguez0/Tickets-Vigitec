<x-agent.layout>
    @push('styles')
    <style>
        .org-detail-header-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }
        .org-nav-tabs {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1.5rem;
            display: flex;
            gap: 8px;
        }
        .org-nav-link {
            color: rgba(255, 255, 255, 0.65);
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            border-bottom: 2px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }
        .org-nav-link:hover {
            color: #fff;
        }
        .org-nav-link.active {
            color: #ef4444;
            border-bottom-color: #ef4444;
        }
        .org-table-container {
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
    </style>
    @endpush

    <div class="container-fluid px-0">
        <!-- Navegación y acciones principales -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('agent.users.orgs') }}" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 8px;">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Organizaciones
                </a>
                <div>
                    <h1 class="h3 text-white mb-0 fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-building text-danger"></i>
                        <span>{{ $organizacion->name }}</span>
                    </h1>
                    <span class="text-muted small">Organización #{{ $organizacion->id }}</span>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-outline-light d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalEditarOrganizacion" style="border-radius: 8px;">
                    <i class="bi bi-pencil"></i>
                    <span>Editar</span>
                </button>
                <form action="{{ route('agent.organizations.destroy', $organizacion->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta organización?');" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                        <i class="bi bi-trash"></i>
                        <span>Eliminar</span>
                    </button>
                </form>
            </div>
        </div>


        @if ($errors->any())
            <div class="alert alert-danger mb-4 border-0 rounded-3 shadow-sm" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Ocurrieron errores:</div>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tarjeta de información principal -->
        <div class="org-detail-header-card">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Teléfono</div>
                    <div class="text-white fw-bold">
                        {{ $organizacion->phone ?: 'No registrado' }}
                        @if ($organizacion->phone_ext)
                            <span class="text-muted small">Ext. {{ $organizacion->phone_ext }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Sitio Web</div>
                    <div class="text-white">
                        @if ($organizacion->website)
                            <a href="{{ $organizacion->website }}" target="_blank" class="text-info text-decoration-none">
                                {{ $organizacion->website }} <i class="bi bi-box-arrow-up-right small"></i>
                            </a>
                        @else
                            <span class="text-muted">No registrado</span>
                        @endif
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Dirección</div>
                    <div class="text-white">
                        {{ $organizacion->address ?: 'Sin dirección registrada' }}
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="text-muted small text-uppercase fw-semibold mb-1">Registrado Desde</div>
                    <div class="text-white">
                        {{ $organizacion->created ? $organizacion->created->format('d/m/Y') : '—' }}
                    </div>
                </div>
            </div>

            @if ($organizacion->notes)
                <hr class="my-3" style="border-color: rgba(255,255,255,0.08);">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Notas Internas:</span>
                    <p class="text-white-50 small mb-0">{{ $organizacion->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Pestañas de navegación: Usuarios / Tickets -->
        <div class="org-nav-tabs">
            <a href="{{ route('agent.organizations.show', ['id' => $organizacion->id, 't' => 'usuarios']) }}" class="org-nav-link {{ $pestanaActiva === 'usuarios' ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Usuarios Miembros</span>
                <span class="badge bg-dark border border-secondary ms-1">{{ $usuarios->total() }}</span>
            </a>
            <a href="{{ route('agent.organizations.show', ['id' => $organizacion->id, 't' => 'tickets']) }}" class="org-nav-link {{ $pestanaActiva === 'tickets' ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i>
                <span>Historial de Tickets</span>
                <span class="badge bg-dark border border-secondary ms-1">{{ $tickets->total() }}</span>
            </a>
        </div>

        <!-- Contenido de las pestañas -->
        @if ($pestanaActiva === 'usuarios')
            <!-- Pestaña Usuarios -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white fw-bold mb-0">Miembros de la Organización</h5>
                <button type="button" class="btn btn-sm btn-danger fw-semibold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalVincularMiembro" style="border-radius: 8px;">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Vincular Usuario</span>
                </button>
            </div>

            <div class="org-table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo Electrónico</th>
                            <th>Teléfono</th>
                            <th>Tickets Creados</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usr)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; background: rgba(239,68,68,0.2); font-size: 0.8rem;">
                                            {{ $usr->iniciales }}
                                        </div>
                                        <div>
                                            <a href="{{ route('agent.users.show', $usr->id) }}" class="text-white text-decoration-none fw-semibold">
                                                {{ $usr->nombre_completo }}
                                            </a>
                                            @if ($usr->org_tickets_view)
                                                <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Jefe Org</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $usr->email }}</td>
                                <td>{{ $usr->phone ?: '—' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $usr->tickets_count ?? $usr->tickets()->count() }} tickets</span>
                                </td>
                                <td>
                                    @if ($usr->esActivo())
                                        <span class="badge-status" style="background: rgba(34,197,94,0.15); color: #4ade80;">Activo</span>
                                    @else
                                        <span class="badge-status" style="background: rgba(148,163,184,0.15); color: #94a3b8;">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('agent.organizations.members.destroy', ['id' => $organizacion->id, 'userId' => $usr->id]) }}" method="POST" onsubmit="return confirm('¿Desvincular a este usuario de la organización?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary text-danger" title="Desvincular de organización" style="border-radius: 6px;">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-people d-block fs-3 mb-2 opacity-50"></i>
                                    No hay usuarios vinculados a esta organización.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($usuarios->hasPages())
                    <div class="p-3 border-top border-secondary" style="border-color: rgba(255,255,255,0.08) !important;">
                        {{ $usuarios->appends(['t' => 'usuarios'])->links() }}
                    </div>
                @endif
            </div>

        @else
            <!-- Pestaña Tickets -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white fw-bold mb-0">Tickets de la Organización</h5>
                <a href="{{ route('agent.tickets.create') }}" class="btn btn-sm btn-danger fw-semibold d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                    <i class="bi bi-plus-lg"></i>
                    <span>Abrir Ticket</span>
                </a>
            </div>

            <div class="org-table-container">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th># Ticket</th>
                            <th>Asunto</th>
                            <th>Usuario / Solicitante</th>
                            <th>Departamento</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $tck)
                            <tr>
                                <td>
                                    <a href="{{ route('agent.tickets.show', $tck->id) }}" class="text-danger fw-bold text-decoration-none">
                                        #{{ $tck->ticket_number }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('agent.tickets.show', $tck->id) }}" class="text-white text-decoration-none fw-semibold">
                                        {{ $tck->subject }}
                                    </a>
                                </td>
                                <td>{{ $tck->user ? $tck->user->nombre_completo : '—' }}</td>
                                <td>{{ $tck->department ? $tck->department->name : 'General' }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $tck->priority->color ?? ($tck->priority->priority_color ?? '#64748b') }}; color: #fff;">
                                        {{ $tck->priority->name ?? ($tck->priority->priority_desc ?? 'Normal') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge" style="background-color: {{ $tck->status->color ?? '#3b82f6' }}; color: #fff;">
                                        {{ $tck->status->name ?? 'Abierto' }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $tck->created ? \Carbon\Carbon::parse($tck->created)->format('d/m/Y H:i') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="bi bi-ticket-perforated d-block fs-3 mb-2 opacity-50"></i>
                                    No hay tickets asociados a miembros de esta organización.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($tickets->hasPages())
                    <div class="p-3 border-top border-secondary" style="border-color: rgba(255,255,255,0.08) !important;">
                        {{ $tickets->appends(['t' => 'tickets'])->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Modal Editar Organización -->
    <x-modal 
        id="modalEditarOrganizacion"
        titulo="Editar Organización"
        subtitulo="Actualiza la información comercial y de contacto"
        icono="bi-pencil-square"
        colorIcono="text-danger"
        :accion="route('agent.organizations.update', $organizacion->id)"
        metodo="PUT"
        textoBoton="Guardar Cambios"
        iconoBoton="bi-check-lg"
        colorBoton="btn-danger"
        tamano="modal-lg"
    >
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Nombre de la Organización <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ $organizacion->name }}" required>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Teléfono</label>
                <input type="text" name="phone" class="form-control" value="{{ $organizacion->phone }}">
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Extensión Telefónica</label>
                <input type="text" name="phone_ext" class="form-control" value="{{ $organizacion->phone_ext }}">
            </div>

            <div class="col-12">
                <label class="form-label">Sitio Web</label>
                <input type="url" name="website" class="form-control" value="{{ $organizacion->website }}">
            </div>

            <div class="col-12">
                <label class="form-label">Dirección Física</label>
                <textarea name="address" class="form-control" rows="2">{{ $organizacion->address }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Notas Internas</label>
                <textarea name="notes" class="form-control" rows="3">{{ $organizacion->notes }}</textarea>
            </div>

            <div class="col-12">
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" type="checkbox" name="plain_text_emails" value="1" id="editPlainTextCheck" {{ $organizacion->plain_text_emails ? 'checked' : '' }}>
                    <label class="form-check-label text-white small" for="editPlainTextCheck">
                        Enviar correos electrónicos solo en texto plano
                    </label>
                </div>
            </div>
        </div>
    </x-modal>

    <!-- Modal Vincular Miembro -->
    <x-modal 
        id="modalVincularMiembro"
        titulo="Vincular Usuario a la Organización"
        subtitulo="Selecciona un cliente existente para agregarlo a {{ $organizacion->name }}"
        icono="bi-person-plus-fill"
        colorIcono="text-info"
        :accion="route('agent.organizations.members.store', $organizacion->id)"
        metodo="POST"
        textoBoton="Vincular Usuario"
        iconoBoton="bi-link-45deg"
        colorBoton="btn-info text-white"
        tamano="modal-md"
    >
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Seleccionar Usuario <span class="text-danger">*</span></label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Selecciona un usuario --</option>
                    @foreach ($usuariosCandidatos as $cand)
                        <option value="{{ $cand->id }}">
                            {{ $cand->nombre_completo }} ({{ $cand->email }})
                        </option>
                    @endforeach
                </select>
                <div class="form-hint">Se asociará a este usuario con la organización actual.</div>
            </div>
        </div>
    </x-modal>
</x-agent.layout>
