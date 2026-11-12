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
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="emails-hero">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="emails-hero-icon">
                    <i class="bi bi-envelope-at"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Cuentas de Correo Electrónico</h1>
                    <p class="text-secondary small mb-0">Administra las cuentas de correo del sistema para el envío de notificaciones y recepción de tickets.</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.emailtest') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold small">
                    <i class="bi bi-activity me-1"></i> Probar Conectividad
                </a>
                <button type="button" class="btn btn-danger rounded-pill px-3 py-2 fw-semibold shadow-sm small" style="background-color: #ef4444; border-color: #ef4444;" data-bs-toggle="modal" data-bs-target="#modalNuevaCuenta">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Cuenta
                </button>
            </div>
        </div>
    </div>

    <!-- Barra de Control -->
    <div class="emails-control-bar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2 text-secondary">
                <i class="bi bi-inbox me-1"></i> Cuentas Activas
            </span>
        </div>
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
                                            class="btn btn-sm btn-outline-light border-0 rounded-circle btn-editar-cuenta" 
                                            title="Editar"
                                            data-id="{{ $cuenta->id }}"
                                            data-action="{{ route('admin.email.update', $cuenta->id) }}"
                                            data-email="{{ $cuenta->email }}"
                                            data-name="{{ $cuenta->name }}"
                                            data-priority="{{ $cuenta->priority }}"
                                            data-dept-id="{{ $cuenta->dept_id }}"
                                            data-smtp-host="{{ $cuenta->smtp_host }}"
                                            data-smtp-port="{{ $cuenta->smtp_port ?? 587 }}"
                                            data-smtp-secure="{{ $cuenta->smtp_secure ?? 'none' }}"
                                            data-smtp-user="{{ $cuenta->smtp_user }}"
                                            data-is-default="{{ $cuenta->is_default ? '1' : '0' }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarCuenta">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-eliminar-cuenta" 
                                            title="Eliminar cuenta"
                                            data-action="{{ route('admin.email.destroy', $cuenta->id) }}"
                                            data-email="{{ $cuenta->email }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminarCuenta">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal Registrar Cuenta usando componente x-agent.modal -->
    <x-agent.modal 
        id="modalNuevaCuenta" 
        titulo="Registrar Nueva Cuenta de Correo" 
        subtitulo="Configura una dirección de correo para emitir y recibir tickets" 
        icono="bi-envelope-plus" 
        colorIcono="text-danger" 
        accion="{{ route('admin.email.store') }}" 
        metodo="POST" 
        textoBoton="Registrar Cuenta" 
        colorBoton="btn-danger" 
        iconoBoton="bi-check-circle"
        tamano="modal-lg"
    >
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Correo Electrónico *</label>
                <input type="email" name="email" class="form-control" placeholder="soporte@tuempresa.com" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Nombre del Remitente *</label>
                <input type="text" name="name" class="form-control" placeholder="Soporte Técnico" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Prioridad por Defecto *</label>
                <select name="priority" class="form-select" required>
                    <option value="Baja">Baja</option>
                    <option value="Normal" selected>Normal</option>
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
                    <option value="none">Ninguna</option>
                    <option value="tls" selected>TLS / STARTTLS</option>
                    <option value="ssl">SSL</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Usuario SMTP</label>
                <input type="text" name="smtp_user" class="form-control" placeholder="usuario@ejemplo.com">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Contraseña SMTP</label>
                <input type="password" name="smtp_pass" class="form-control" placeholder="••••••••">
            </div>
            <div class="col-12 mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="nueva_is_default">
                    <label class="form-check-label text-secondary small" for="nueva_is_default">
                        Marcar como cuenta de correo predeterminada del sistema
                    </label>
                </div>
            </div>
        </div>
    </x-agent.modal>

    <!-- Modal Editar Cuenta usando componente x-agent.modal -->
    <x-agent.modal 
        id="modalEditarCuenta" 
        titulo="Editar Cuenta de Correo" 
        subtitulo="Modifica los parámetros de conexión y asignación" 
        icono="bi-pencil-square" 
        colorIcono="text-danger" 
        accion="#" 
        metodo="PUT" 
        textoBoton="Guardar Cambios" 
        colorBoton="btn-danger" 
        iconoBoton="bi-check-circle"
        tamano="modal-lg"
    >
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Correo Electrónico *</label>
                <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Nombre del Remitente *</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Prioridad *</label>
                <select name="priority" id="edit_priority" class="form-select" required>
                    <option value="Baja">Baja</option>
                    <option value="Normal">Normal</option>
                    <option value="Alta">Alta</option>
                    <option value="Urgente">Urgente</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-bold text-secondary">Departamento Asociado</label>
                <select name="dept_id" id="edit_dept_id" class="form-select">
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
                <input type="text" name="smtp_host" id="edit_smtp_host" class="form-control" placeholder="smtp.ejemplo.com">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Puerto SMTP</label>
                <input type="number" name="smtp_port" id="edit_smtp_port" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Seguridad</label>
                <select name="smtp_secure" id="edit_smtp_secure" class="form-select">
                    <option value="none">Ninguna</option>
                    <option value="tls">TLS / STARTTLS</option>
                    <option value="ssl">SSL</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Usuario SMTP</label>
                <input type="text" name="smtp_user" id="edit_smtp_user" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Contraseña SMTP</label>
                <input type="password" name="smtp_pass" id="edit_smtp_pass" class="form-control" placeholder="Dejar en blanco para conservar">
            </div>
            <div class="col-12 mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="edit_is_default">
                    <label class="form-check-label text-secondary small" for="edit_is_default">
                        Marcar como cuenta de correo predeterminada del sistema
                    </label>
                </div>
            </div>
        </div>
    </x-agent.modal>

    <!-- Modal Eliminar Cuenta usando componente x-delete-modal -->
    <x-delete-modal
        id="modalEliminarCuenta"
        title="Eliminar Cuenta de Correo"
        message="¿Estás seguro de que deseas eliminar esta cuenta de correo? Esta acción no se puede deshacer y puede afectar la entrega de mensajes."
        submitText="Eliminar Cuenta"
        formAction="#"
    />

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Llenado dinámico del modal de edición
            const botonesEditar = document.querySelectorAll('.btn-editar-cuenta');
            const formularioEditar = document.getElementById('modalEditarCuenta-formulario');

            botonesEditar.forEach(btn => {
                btn.addEventListener('click', function () {
                    if (formularioEditar) {
                        formularioEditar.action = this.dataset.action;
                    }
                    document.getElementById('edit_email').value = this.dataset.email || '';
                    document.getElementById('edit_name').value = this.dataset.name || '';
                    document.getElementById('edit_priority').value = this.dataset.priority || 'Normal';
                    document.getElementById('edit_dept_id').value = this.dataset.deptId || '';
                    document.getElementById('edit_smtp_host').value = this.dataset.smtpHost || '';
                    document.getElementById('edit_smtp_port').value = this.dataset.smtpPort || 587;
                    document.getElementById('edit_smtp_secure').value = this.dataset.smtpSecure || 'none';
                    document.getElementById('edit_smtp_user').value = this.dataset.smtpUser || '';
                    document.getElementById('edit_smtp_pass').value = '';
                    document.getElementById('edit_is_default').checked = this.dataset.isDefault === '1';
                });
            });

            // Configuración dinámica del modal de eliminación
            const botonesEliminar = document.querySelectorAll('.btn-eliminar-cuenta');
            const formularioEliminar = document.getElementById('modalEliminarCuenta-form');

            botonesEliminar.forEach(btn => {
                btn.addEventListener('click', function () {
                    if (formularioEliminar) {
                        formularioEliminar.action = this.dataset.action;
                    }
                });
            });
        });
    </script>
    @endpush
</x-agent.admin-layout>
