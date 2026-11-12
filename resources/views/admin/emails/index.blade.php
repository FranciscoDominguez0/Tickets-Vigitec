<x-agent.admin-layout>
    @push('styles')
    <style>
        .emails-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .emails-hero-icon {
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

        .emails-control-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .emails-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .emails-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .emails-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .emails-table td {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .emails-table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .badge-predeterminada {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .modal-dark .modal-content {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 14px;
        }

        .modal-dark .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .modal-dark .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .modal-dark .form-control,
        .modal-dark .form-select {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .modal-dark .form-control:focus,
        .modal-dark .form-select:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="emails-hero">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="emails-hero-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Cuentas de Correo Electrónico</h1>
                    <p class="text-secondary small mb-0">Configura y administra las cuentas de correo del sistema para notificaciones y soporte.</p>
                </div>
            </div>
            <button type="button" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta" style="background-color: #ef4444; border-color: #ef4444;">
                <i class="bi bi-plus-lg me-1"></i> Nueva Cuenta
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-4 d-flex align-items-center gap-2" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-4 d-flex align-items-center gap-2" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Barra de Control -->
    <div class="emails-control-bar d-flex align-items-center justify-content-between">
        <div class="text-secondary small fw-semibold">
            Total de cuentas registradas: <strong class="text-white">{{ $cuentas->count() }}</strong>
        </div>
    </div>

    <!-- Tabla de Cuentas -->
    <div class="emails-table-wrapper">
        @if($cuentas->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-envelope-x fs-1 mb-2 d-block opacity-50"></i>
                <h5 class="fw-bold text-white">No hay cuentas de correo registradas</h5>
                <p class="small text-secondary mb-3">Registra una cuenta para poder enviar y recibir correos de tickets.</p>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                    Agregar la primera cuenta
                </button>
            </div>
        @else
            <table class="emails-table">
                <thead>
                    <tr>
                        <th>Correo Electrónico</th>
                        <th>Remitente</th>
                        <th>Prioridad</th>
                        <th>Departamento</th>
                        <th>Servidor Saliente (SMTP)</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cuentas as $cuenta)
                        <tr>
                            <td>
                                <div class="fw-bold text-white d-flex align-items-center gap-2">
                                    <i class="bi bi-envelope text-secondary"></i>
                                    {{ $cuenta->email }}
                                </div>
                            </td>
                            <td>{{ $cuenta->name }}</td>
                            <td>
                                <span class="badge rounded-pill px-2 py-1 small" style="background: rgba(255, 255, 255, 0.08); color: #cbd5e1;">
                                    {{ $cuenta->priority ?? 'Normal' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-secondary">{{ $cuenta->departamento->name ?? 'Sin asignar' }}</span>
                            </td>
                            <td>
                                @if($cuenta->smtp_host)
                                    <code class="text-info bg-transparent p-0">{{ $cuenta->smtp_host }}:{{ $cuenta->smtp_port ?? 587 }}</code>
                                    <small class="text-secondary d-block">({{ strtoupper($cuenta->smtp_secure ?? 'tls') }})</small>
                                @else
                                    <span class="text-muted small">No configurado</span>
                                @endif
                            </td>
                            <td>
                                @if($cuenta->is_default)
                                    <span class="badge-predeterminada">
                                        <i class="bi bi-star-fill"></i> Predeterminada
                                    </span>
                                @else
                                    <form action="{{ route('admin.email.default', $cuenta->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" style="font-size: 0.72rem;" title="Establecer como cuenta por defecto">
                                            Hacer predeterminada
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-light border-0 rounded-circle" 
                                            title="Editar"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarCuenta{{ $cuenta->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('admin.email.destroy', $cuenta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta de correo?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar Cuenta -->
                        <div class="modal fade modal-dark" id="modalEditarCuenta{{ $cuenta->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('admin.email.update', $cuenta->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold text-white">Editar Cuenta de Correo: {{ $cuenta->email }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-secondary">Correo Electrónico *</label>
                                                    <input type="email" name="email" class="form-control" value="{{ old('email', $cuenta->email) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-secondary">Nombre del Remitente *</label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $cuenta->name) }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-secondary">Prioridad *</label>
                                                    <select name="priority" class="form-select" required>
                                                        @foreach(['Baja', 'Normal', 'Alta', 'Urgente'] as $prio)
                                                            <option value="{{ $prio }}" {{ old('priority', $cuenta->priority) == $prio ? 'selected' : '' }}>{{ $prio }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-secondary">Departamento Asociado</label>
                                                    <select name="dept_id" class="form-select">
                                                        <option value="">— Sin asignar —</option>
                                                        @foreach($departamentos as $dept)
                                                            <option value="{{ $dept->id }}" {{ old('dept_id', $cuenta->dept_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <h6 class="fw-bold text-danger border-bottom border-secondary pb-2 mb-3 mt-4">
                                                <i class="bi bi-send-check me-1"></i> Configuración Servidor Saliente (SMTP)
                                            </h6>

                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <label class="form-label small fw-bold text-secondary">Host Servidor SMTP</label>
                                                    <input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host', $cuenta->smtp_host) }}" placeholder="smtp.ejemplo.com">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-secondary">Puerto SMTP</label>
                                                    <input type="number" name="smtp_port" class="form-control" value="{{ old('smtp_port', $cuenta->smtp_port ?? 587) }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-secondary">Seguridad</label>
                                                    <select name="smtp_secure" class="form-select">
                                                        <option value="none" {{ old('smtp_secure', $cuenta->smtp_secure) == 'none' ? 'selected' : '' }}>Ninguna</option>
                                                        <option value="tls" {{ old('smtp_secure', $cuenta->smtp_secure) == 'tls' ? 'selected' : '' }}>TLS / STARTTLS</option>
                                                        <option value="ssl" {{ old('smtp_secure', $cuenta->smtp_secure) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-secondary">Usuario SMTP</label>
                                                    <input type="text" name="smtp_user" class="form-control" value="{{ old('smtp_user', $cuenta->smtp_user) }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-secondary">Contraseña SMTP</label>
                                                    <input type="password" name="smtp_pass" class="form-control" placeholder="Dejar en blanco para conservar">
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="edit_is_default{{ $cuenta->id }}" {{ $cuenta->is_default ? 'checked' : '' }}>
                                                        <label class="form-check-label text-secondary small" for="edit_is_default{{ $cuenta->id }}">
                                                            Marcar como cuenta de correo predeterminada del sistema
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background-color: #ef4444; border-color: #ef4444;">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal Nueva Cuenta -->
    <div class="modal fade modal-dark" id="modalNuevaCuenta" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.email.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-white">Registrar Nueva Cuenta de Correo</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Correo Electrónico *</label>
                                <input type="email" name="email" class="form-control" placeholder="soporte@empresa.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nombre del Remitente *</label>
                                <input type="text" name="name" class="form-control" placeholder="Mesa de Ayuda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Prioridad *</label>
                                <select name="priority" class="form-select" required>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Baja">Baja</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Urgente">Urgente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Departamento Asociado</label>
                                <select name="dept_id" class="form-select">
                                    <option value="">— Sin asignar —</option>
                                    @foreach($departamentos as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h6 class="fw-bold text-danger border-bottom border-secondary pb-2 mb-3 mt-4">
                            <i class="bi bi-send-check me-1"></i> Configuración Servidor Saliente (SMTP)
                        </h6>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-secondary">Host Servidor SMTP</label>
                                <input type="text" name="smtp_host" class="form-control" placeholder="smtp.ejemplo.com">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Puerto SMTP</label>
                                <input type="number" name="smtp_port" class="form-control" value="587">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Seguridad</label>
                                <select name="smtp_secure" class="form-select">
                                    <option value="tls" selected>TLS / STARTTLS</option>
                                    <option value="ssl">SSL</option>
                                    <option value="none">Ninguna</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Usuario SMTP</label>
                                <input type="text" name="smtp_user" class="form-control" placeholder="usuario@ejemplo.com">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-secondary">Contraseña SMTP</label>
                                <input type="password" name="smtp_pass" class="form-control">
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="new_is_default">
                                    <label class="form-check-label text-secondary small" for="new_is_default">
                                        Marcar como cuenta de correo predeterminada del sistema
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background-color: #ef4444; border-color: #ef4444;">Crear Cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-agent.admin-layout>

