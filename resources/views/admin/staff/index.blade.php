<x-agent.admin-layout>
    @push('styles')
    <style>
        .staff-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .staff-hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(14, 165, 233, 0.15);
            color: #0ea5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(14, 165, 233, 0.25);
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

        .staff-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .staff-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .staff-table th {
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

        .staff-table td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.88rem;
            color: #e2e8f0;
        }

        .staff-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
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
            border: 1px solid rgba(14, 165, 233, 0.3);
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

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show bg-warning text-dark border-0 mb-4 shadow" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
    <div class="staff-hero d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="staff-hero-icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-1">Directorio de Personal y Agentes</h4>
                <p class="text-muted small mb-0">Administra las cuentas de soporte, asignación de departamentos, roles y credenciales de acceso.</p>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold px-4 py-2" data-bs-toggle="modal" data-bs-target="#crearAgenteModal">
                <i class="bi bi-person-plus-fill fs-6"></i>
                <span>Nuevo Agente</span>
            </button>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(14, 165, 233, 0.15); color: #0ea5e9;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['total']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Total Personal</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['activos']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Agentes Activos</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                    <i class="bi bi-person-x-fill"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['inactivos']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Inactivos</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(168, 85, 247, 0.15); color: #a855f7;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <div class="text-white fw-bold fs-5">{{ number_format($estadisticas['administradores']) }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">Administradores</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <div class="control-bar">
        <form action="{{ route('admin.staff') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-black border-secondary text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" value="{{ $busqueda }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Buscar por nombre, usuario, correo...">
                </div>
            </div>

            <div class="col-md-3">
                <select name="dept_id" class="form-select form-select-sm bg-black text-white border-secondary shadow-none">
                    <option value="0">Todos los Departamentos</option>
                    @foreach($departamentos as $dept)
                        <option value="{{ $dept->id }}" {{ $filtroDept === $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="role" class="form-select form-select-sm bg-black text-white border-secondary shadow-none">
                    <option value="todos" {{ $filtroRol === 'todos' ? 'selected' : '' }}>Todos los Roles</option>
                    <option value="admin" {{ $filtroRol === 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="agent" {{ $filtroRol === 'agent' ? 'selected' : '' }}>Agente Estándar</option>
                    @foreach($roles as $r)
                        @if(!in_array($r->name, ['admin', 'agent'], true))
                            <option value="{{ $r->name }}" {{ $filtroRol === $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm bg-black text-white border-secondary shadow-none">
                    <option value="todos" {{ $filtroEstado === 'todos' ? 'selected' : '' }}>Todos los Estados</option>
                    <option value="activos" {{ $filtroEstado === 'activos' ? 'selected' : '' }}>Solo Activos</option>
                    <option value="inactivos" {{ $filtroEstado === 'inactivos' ? 'selected' : '' }}>Solo Inactivos</option>
                </select>
            </div>

            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm fw-bold w-100">
                    <i class="bi bi-funnel"></i>
                </button>
                @if(!empty($busqueda) || $filtroDept > 0 || $filtroRol !== 'todos' || $filtroEstado !== 'todos')
                    <a href="{{ route('admin.staff') }}" class="btn btn-dark btn-sm border-secondary text-muted">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Barra flotante de acciones masivas -->
    <div class="batch-bar" id="batchBar">
        <form action="{{ route('admin.staff.mass') }}" method="POST" id="formAccionMasiva" class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            @csrf
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary text-white fw-bold" id="contadorSeleccionados">0</span>
                <span class="text-white small">agentes seleccionados</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <select name="accion" class="form-select form-select-sm bg-black text-white border-secondary shadow-none" style="width: auto;" required>
                    <option value="">Acción por lotes...</option>
                    <option value="activar">Habilitar seleccionados</option>
                    <option value="desactivar">Deshabilitar seleccionados</option>
                    <option value="eliminar">Eliminar seleccionados</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm fw-semibold px-3" onclick="return confirm('¿Deseas aplicar esta acción a los agentes seleccionados?');">
                    Aplicar
                </button>
            </div>
            <div id="contenedorIdsOcultos"></div>
        </form>
    </div>

    <!-- Tabla de Agentes -->
    <div class="staff-table-wrapper">
        <table class="staff-table">
            <thead>
                <tr>
                    <th style="width: 40px;" class="text-center">
                        <input type="checkbox" id="checkTodo" class="form-check-input bg-black border-secondary">
                    </th>
                    <th>Personal / Contacto</th>
                    <th style="width: 140px;">Usuario</th>
                    <th style="width: 220px;">Departamento(s)</th>
                    <th style="width: 130px;">Rol</th>
                    <th style="width: 110px;">Estado</th>
                    <th style="width: 100px;" class="text-center">Tickets</th>
                    <th style="width: 150px;">Último Acceso</th>
                    <th style="width: 140px;" class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agentes as $agente)
                    @php
                        $esMismoUsuario = ($agente->id === (int) auth('staff')->id());
                    @endphp
                    <tr>
                        <td class="text-center">
                            @if(!$esMismoUsuario)
                                <input type="checkbox" class="form-check-input item-check bg-black border-secondary" value="{{ $agente->id }}">
                            @else
                                <span class="text-muted" title="Tu cuenta"><i class="bi bi-shield-check"></i></span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary bg-opacity-25 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-weight: 600;">
                                    {{ strtoupper(substr($agente->firstname, 0, 1)) }}{{ strtoupper(substr($agente->lastname, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-white d-flex align-items-center gap-1">
                                        {{ $agente->nombreCompleto() }}
                                        @if($esMismoUsuario)
                                            <span class="badge bg-warning text-dark px-1 py-0" style="font-size: 0.65rem;">Tú</span>
                                        @endif
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $agente->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-info font-monospace small">@<!-- -->{{ $agente->username }}</span>
                        </td>
                        <td>
                            @if($agente->department)
                                <span class="badge bg-dark border border-secondary text-light">
                                    <i class="bi bi-diagram-3 me-1"></i> {{ $agente->department->name }}
                                </span>
                            @else
                                <span class="text-muted fst-italic small">Sin depto. principal</span>
                            @endif

                            @if($agente->departamentosAdicionales->isNotEmpty())
                                <div class="mt-1 d-flex flex-wrap gap-1">
                                    @foreach($agente->departamentosAdicionales as $deptExtra)
                                        <span class="badge bg-secondary bg-opacity-25 text-muted border border-secondary" style="font-size: 0.65rem;">
                                            + {{ $deptExtra->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($agente->esAdmin())
                                <span class="badge bg-danger text-white px-2 py-1">
                                    <i class="bi bi-shield-lock me-1"></i> Administrador
                                </span>
                            @else
                                <span class="badge bg-primary text-white px-2 py-1">
                                    <i class="bi bi-headset me-1"></i> {{ ucfirst($agente->role) }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if(!$esMismoUsuario)
                                <form action="{{ route('admin.staff.toggle', $agente->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn p-0 border-0 shadow-none" title="Clic para alternar estado">
                                        @if($agente->is_active)
                                            <span class="pill-badge bg-success bg-opacity-25 text-success border border-success">
                                                <i class="bi bi-check-circle-fill"></i> Activo
                                            </span>
                                        @else
                                            <span class="pill-badge bg-danger bg-opacity-25 text-danger border border-danger">
                                                <i class="bi bi-dash-circle-fill"></i> Inactivo
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            @else
                                <span class="pill-badge bg-success bg-opacity-25 text-success border border-success">
                                    <i class="bi bi-check-circle-fill"></i> Activo
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($agente->total_tickets > 0)
                                <span class="badge bg-info text-dark fw-bold">
                                    {{ $agente->total_tickets }}
                                </span>
                            @else
                                <span class="text-muted small">0</span>
                            @endif
                        </td>
                        <td>
                            <div class="text-white small">{{ $agente->last_login ? $agente->last_login->format('d/m/Y H:i') : 'Nunca' }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ $agente->last_login ? $agente->last_login->diffForHumans() : '-' }}</div>
                        </td>
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <!-- Botón Editar -->
                                <button type="button" class="btn btn-sm btn-outline-info p-1 px-2" data-bs-toggle="modal" data-bs-target="#editarAgenteModal{{ $agente->id }}" title="Editar datos">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <!-- Botón Restablecer Clave -->
                                <button type="button" class="btn btn-sm btn-outline-warning p-1 px-2" data-bs-toggle="modal" data-bs-target="#resetearClaveModal{{ $agente->id }}" title="Restablecer Contraseña">
                                    <i class="bi bi-key-fill"></i>
                                </button>

                                <!-- Botón Eliminar -->
                                @if(!$esMismoUsuario)
                                    <button type="button" class="btn btn-sm btn-outline-danger p-1 px-2" data-bs-toggle="modal" data-bs-target="#eliminarAgenteModal{{ $agente->id }}" title="Eliminar agente">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal Editar Agente -->
                    <x-agent.modal id="editarAgenteModal{{ $agente->id }}" title="Editar Agente: {{ $agente->nombreCompleto() }}" size="modal-lg">
                        <form action="{{ route('admin.staff.update', $agente->id) }}" method="POST" id="formEditarAgente{{ $agente->id }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3 text-start">
                                <div class="col-md-6">
                                    <label class="form-label text-white small fw-bold">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" value="{{ old('firstname', $agente->firstname) }}" class="form-control bg-black text-white border-secondary shadow-none" required maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white small fw-bold">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" value="{{ old('lastname', $agente->lastname) }}" class="form-control bg-black text-white border-secondary shadow-none" required maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white small fw-bold">Nombre de Usuario <span class="text-danger">*</span></label>
                                    <input type="text" name="username" value="{{ old('username', $agente->username) }}" class="form-control bg-black text-white border-secondary shadow-none" required maxlength="50">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white small fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $agente->email) }}" class="form-control bg-black text-white border-secondary shadow-none" required maxlength="150">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white small fw-bold">Departamento Principal</label>
                                    <select name="dept_id" class="form-select bg-black text-white border-secondary shadow-none">
                                        <option value="">Sin departamento asignado</option>
                                        @foreach($departamentos as $dept)
                                            <option value="{{ $dept->id }}" {{ old('dept_id', $agente->dept_id) == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-white small fw-bold">Rol de Acceso <span class="text-danger">*</span></label>
                                    <select name="role" class="form-select bg-black text-white border-secondary shadow-none" required {{ $esMismoUsuario ? 'disabled' : '' }}>
                                        <option value="admin" {{ old('role', $agente->role) === 'admin' ? 'selected' : '' }}>Administrador (Control total)</option>
                                        <option value="agent" {{ old('role', $agente->role) === 'agent' ? 'selected' : '' }}>Agente Estándar</option>
                                        @foreach($roles as $r)
                                            @if(!in_array($r->name, ['admin', 'agent'], true))
                                                <option value="{{ $r->name }}" {{ old('role', $agente->role) === $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($esMismoUsuario)
                                        <input type="hidden" name="role" value="{{ $agente->role }}">
                                        <div class="form-text text-muted small">No puedes cambiar tu propio rol en sesión.</div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white small fw-bold">Departamentos Adicionales (Acceso compartido)</label>
                                    @php
                                        $deptosAsignados = $agente->departamentosAdicionales->pluck('id')->toArray();
                                    @endphp
                                    <div class="p-3 rounded bg-black border border-secondary row g-2">
                                        @forelse($departamentos as $deptExtra)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="departamentos_adicionales[]" value="{{ $deptExtra->id }}" id="deptExtraEdit{{ $agente->id }}_{{ $deptExtra->id }}" {{ in_array($deptExtra->id, old('departamentos_adicionales', $deptosAsignados), true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-white small" for="deptExtraEdit{{ $agente->id }}_{{ $deptExtra->id }}">
                                                        {{ $deptExtra->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-muted small">No hay departamentos registrados.</div>
                                        @endforelse
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white small fw-bold">Firma Opcional</label>
                                    <textarea name="signature" class="form-control bg-black text-white border-secondary shadow-none" rows="2" placeholder="Firma que se adjuntará al responder tickets...">{{ old('signature', $agente->signature) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check form-switch p-3 rounded bg-black border border-secondary">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="checkActivoEdit{{ $agente->id }}" value="1" {{ old('is_active', $agente->is_active) ? 'checked' : '' }} {{ $esMismoUsuario ? 'disabled' : '' }}>
                                        <label class="form-check-label text-white small ms-2" for="checkActivoEdit{{ $agente->id }}">
                                            <strong>Cuenta Activa</strong>
                                            <span class="d-block text-muted" style="font-size: 0.78rem;">Permite al agente iniciar sesión y atender solicitudes en el sistema.</span>
                                        </label>
                                        @if($esMismoUsuario)
                                            <input type="hidden" name="is_active" value="1">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                        <x-slot:footer>
                            <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" form="formEditarAgente{{ $agente->id }}" class="btn btn-primary fw-semibold px-4">
                                Guardar Cambios
                            </button>
                        </x-slot:footer>
                    </x-agent.modal>

                    <!-- Modal Restablecer Contraseña -->
                    <x-agent.modal id="resetearClaveModal{{ $agente->id }}" title="Restablecer Contraseña: {{ $agente->nombreCompleto() }}">
                        <form action="{{ route('admin.staff.reset_password', $agente->id) }}" method="POST" id="formResetClave{{ $agente->id }}">
                            @csrf
                            <div class="text-start">
                                <p class="text-muted small mb-3">
                                    Ingresa la nueva contraseña para <strong>{{ $agente->email }}</strong>. El agente podrá iniciar sesión con esta credencial inmediatamente.
                                </p>
                                <div class="mb-3">
                                    <label class="form-label text-white small fw-bold">Nueva Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Mínimo 8 caracteres" required minlength="8">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white small fw-bold">Confirmar Contraseña <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Repite la contraseña" required minlength="8">
                                </div>
                            </div>
                        </form>
                        <x-slot:footer>
                            <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" form="formResetClave{{ $agente->id }}" class="btn btn-warning fw-bold px-4 text-dark">
                                <i class="bi bi-key-fill me-1"></i> Actualizar Contraseña
                            </button>
                        </x-slot:footer>
                    </x-agent.modal>

                    <!-- Modal Eliminar Agente -->
                    @if(!$esMismoUsuario)
                        <x-delete-modal 
                            id="eliminarAgenteModal{{ $agente->id }}" 
                            title="Eliminar Agente: {{ $agente->nombreCompleto() }}" 
                            message="¿Estás seguro de que deseas eliminar este agente del sistema? Si tiene tickets asociados el sistema impedirá su eliminación para proteger el historial."
                            formAction="{{ route('admin.staff.destroy', $agente->id) }}"
                            submitText="Eliminar Agente"
                        />
                    @endif
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i>
                            No se encontraron agentes con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Crear Nuevo Agente -->
    <x-agent.modal id="crearAgenteModal" title="Nuevo Agente de Soporte" size="modal-lg">
        <form action="{{ route('admin.staff.store') }}" method="POST" id="formCrearAgente">
            @csrf
            <div class="row g-3 text-start">
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="firstname" value="{{ old('firstname') }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Ej: Carlos" required maxlength="100">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Apellido <span class="text-danger">*</span></label>
                    <input type="text" name="lastname" value="{{ old('lastname') }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Ej: Mendoza" required maxlength="100">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Nombre de Usuario <span class="text-danger">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Ej: cmendoza" required maxlength="50">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="cmendoza@empresa.com" required maxlength="150">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Contraseña Inicial <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Mínimo 8 caracteres" required minlength="8">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Confirmar Contraseña <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Repite la contraseña" required minlength="8">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Departamento Principal</label>
                    <select name="dept_id" class="form-select bg-black text-white border-secondary shadow-none">
                        <option value="">Sin departamento asignado</option>
                        @foreach($departamentos as $dept)
                            <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white small fw-bold">Rol de Acceso <span class="text-danger">*</span></label>
                    <select name="role" class="form-select bg-black text-white border-secondary shadow-none" required>
                        <option value="agent" {{ old('role', 'agent') === 'agent' ? 'selected' : '' }}>Agente Estándar</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador (Control total)</option>
                        @foreach($roles as $r)
                            @if(!in_array($r->name, ['admin', 'agent'], true))
                                <option value="{{ $r->name }}" {{ old('role') === $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label text-white small fw-bold">Departamentos Adicionales (Acceso compartido)</label>
                    <div class="p-3 rounded bg-black border border-secondary row g-2">
                        @forelse($departamentos as $deptExtra)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="departamentos_adicionales[]" value="{{ $deptExtra->id }}" id="deptExtraCreate{{ $deptExtra->id }}" {{ in_array($deptExtra->id, (array) old('departamentos_adicionales', []), true) ? 'checked' : '' }}>
                                    <label class="form-check-label text-white small" for="deptExtraCreate{{ $deptExtra->id }}">
                                        {{ $deptExtra->name }}
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted small">No hay departamentos registrados.</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label text-white small fw-bold">Firma Opcional</label>
                    <textarea name="signature" class="form-control bg-black text-white border-secondary shadow-none" rows="2" placeholder="Firma que se adjuntará al responder tickets...">{{ old('signature') }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch p-3 rounded bg-black border border-secondary">
                        <input class="form-check-input" type="checkbox" name="is_active" id="checkNuevoActivo" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-white small ms-2" for="checkNuevoActivo">
                            <strong>Cuenta Activa</strong>
                            <span class="d-block text-muted" style="font-size: 0.78rem;">Habilita la cuenta inmediatamente para el agente.</span>
                        </label>
                    </div>
                </div>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formCrearAgente" class="btn btn-primary fw-semibold px-4">
                <i class="bi bi-person-plus-fill me-1"></i> Crear Agente
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
    </script>
    @endpush
</x-agent.admin-layout>

