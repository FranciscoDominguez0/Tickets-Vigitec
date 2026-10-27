<x-agent.layout>
    @push('styles')
    <style>
        .profile-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1.75rem;
        }
        .profile-avatar-lg {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.75rem;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
        }
        .data-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 0.2rem;
        }
        .data-value {
            color: #fff;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .badge-status {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            text-transform: uppercase;
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
        .tickets-table-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
    @endpush

    <div class="container-fluid px-0">
        <!-- Barra de navegación y acciones -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('agent.users.directory') }}" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 8px;">
                    <i class="bi bi-arrow-left me-1"></i> Volver
                </a>
                <div>
                    <h1 class="h3 text-white mb-0 fw-bold">{{ $usuario->nombre_completo }}</h1>
                    <span class="text-muted small">Cliente #{{ $usuario->id }}</span>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('agent.tickets.create', ['user_id' => $usuario->id]) }}" class="btn btn-danger fw-semibold d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                    <i class="bi bi-plus-circle"></i> Nuevo Ticket
                </a>
                <a href="{{ route('agent.users.edit', $usuario->id) }}" class="btn btn-outline-light d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <form action="{{ route('agent.users.toggle_status', $usuario->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
                        <i class="bi {{ $usuario->esActivo() ? 'bi-toggle-on text-success' : 'bi-toggle-off text-muted' }}"></i>
                        <span>{{ $usuario->esActivo() ? 'Desactivar' : 'Activar' }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Alertas de sesión -->
        @if (session('exito'))
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4 border-0 rounded-3 shadow-sm" style="background: rgba(34, 197, 94, 0.15); color: #4ade80;">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('exito') }}</span>
            </div>
        @endif

        <!-- Tarjeta de perfil principal -->
        <div class="profile-card mb-4">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-md-auto text-center text-md-start">
                    <div class="profile-avatar-lg mx-auto">
                        {{ $usuario->iniciales }}
                    </div>
                </div>
                <div class="col-12 col-md">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <h2 class="h4 text-white mb-0 fw-bold">{{ $usuario->nombre_completo }}</h2>
                        @if ($usuario->esActivo())
                            <span class="badge-status badge-active">
                                <i class="bi bi-check-circle-fill"></i> Activo
                            </span>
                        @else
                            <span class="badge-status badge-inactive">
                                <i class="bi bi-dash-circle-fill"></i> Inactivo
                            </span>
                        @endif
                    </div>
                    <p class="text-muted mb-0 small">
                        <i class="bi bi-calendar-event me-1"></i> Registrado el {{ $usuario->created ? $usuario->created->format('d/m/Y H:i') : 'No registrado' }}
                    </p>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">

            <!-- Cuadrícula de datos de contacto -->
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="data-label">Correo Electrónico</div>
                    <div class="data-value">
                        <a href="mailto:{{ $usuario->email }}" class="text-info text-decoration-none">
                            {{ $usuario->email }}
                        </a>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="data-label">Teléfono</div>
                    <div class="data-value">{{ $usuario->phone ?: 'No registrado' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="data-label">Organización / Empresa</div>
                    <div class="data-value">{{ $usuario->company ?: 'Particular' }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="data-label">Dirección</div>
                    <div class="data-value">{{ $usuario->address ?: 'Sin dirección' }}</div>
                </div>
            </div>
        </div>

        <!-- Historial de tickets del usuario -->
        <div class="tickets-table-card">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(255,255,255,0.08) !important;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-ticket-perforated text-danger fs-5"></i>
                    <h3 class="h5 text-white mb-0 fw-bold">Historial de Tickets</h3>
                </div>
                <span class="badge bg-secondary">Total: {{ $usuario->tickets->count() }}</span>
            </div>

            <div class="table-responsive">
                <table class="table table-custom mb-0" style="color: #fff;">
                    <thead>
                        <tr style="background-color: #121417; color: rgba(255,255,255,0.65); font-size: 0.75rem; text-transform: uppercase;">
                            <th class="p-3"># Ticket</th>
                            <th class="p-3">Asunto</th>
                            <th class="p-3">Departamento</th>
                            <th class="p-3">Prioridad</th>
                            <th class="p-3">Estado</th>
                            <th class="p-3">Agente</th>
                            <th class="p-3">Fecha</th>
                            <th class="p-3 text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuario->tickets as $ticket)
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td class="p-3 fw-bold text-danger">
                                    #{{ $ticket->ticket_number ?: $ticket->id }}
                                </td>
                                <td class="p-3">
                                    <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="text-white text-decoration-none fw-semibold">
                                        {{ $ticket->subject }}
                                    </a>
                                </td>
                                <td class="p-3 text-muted">
                                    {{ $ticket->department->name ?? 'General' }}
                                </td>
                                <td class="p-3">
                                    <span class="badge" style="background-color: {{ $ticket->priority->color ?? '#3b82f6' }}20; color: {{ $ticket->priority->color ?? '#3b82f6' }}; border: 1px solid {{ $ticket->priority->color ?? '#3b82f6' }}50;">
                                        {{ $ticket->priority->name ?? 'Normal' }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <span class="badge bg-secondary">
                                        {{ $ticket->status->name ?? 'Abierto' }}
                                    </span>
                                </td>
                                <td class="p-3 text-light small">
                                    {{ $ticket->staff ? ($ticket->staff->firstname . ' ' . $ticket->staff->lastname) : 'Sin asignar' }}
                                </td>
                                <td class="p-3 text-muted small">
                                    {{ $ticket->created ? \Carbon\Carbon::parse($ticket->created)->format('d/m/Y H:i') : '—' }}
                                </td>
                                <td class="p-3 text-end">
                                    <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-danger" title="Ver ticket">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-ticket-perforated d-block mb-2 fs-2 opacity-50"></i>
                                    Este cliente aún no tiene tickets registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-agent.layout>
