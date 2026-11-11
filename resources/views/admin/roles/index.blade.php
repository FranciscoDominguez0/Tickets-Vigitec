<x-agent.admin-layout>
    @push('styles')
    <style>
        .roles-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .roles-hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(37, 99, 235, 0.15);
            color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(37, 99, 235, 0.25);
        }

        .roles-control-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .roles-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .roles-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .roles-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .roles-table td {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .roles-table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .roles-table tbody tr.selected td {
            background-color: rgba(37, 99, 235, 0.08);
        }

        .role-badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .role-badge.active {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .role-badge.inactive {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .agent-count-badge {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            color: #cbd5e1;
            display: inline-block;
            min-width: 32px;
            text-align: center;
        }

        .agent-count-badge.active-agents {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .btn-permissions {
            padding: 6px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-permissions:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* Tarjetas móviles */
        .role-mobile-card {
            padding: 16px;
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .roles-table-wrapper {
                background: transparent;
                border: none;
                box-shadow: none;
            }
            .roles-table { display: block; width: 100%; }
            .roles-table thead { display: none; }
            .roles-table tbody { display: block; width: 100%; }
            .roles-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                background-color: #16191d;
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 14px;
                overflow: hidden;
            }
            .roles-table tbody td.d-md-none {
                display: block;
                width: 100%;
                padding: 0;
                border: none;
            }
            .roles-table tbody td.d-none { display: none; }
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="roles-hero">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <span class="roles-hero-icon"><i class="bi bi-shield-lock"></i></span>
                <div>
                    <h2 class="text-white fw-bold mb-1 fs-4">Roles</h2>
                    <p class="text-secondary mb-0" style="font-size: 0.9rem;">Gestión de roles y permisos del personal</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge px-3 py-2 rounded-pill fw-bold" style="background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.3); color: #38bdf8; font-size: 0.85rem;">
                    <i class="bi bi-shield me-1"></i> {{ $rolesTotal }} Roles
                </span>
                <span class="badge px-3 py-2 rounded-pill fw-bold" style="background: rgba(148, 163, 184, 0.15); border: 1px solid rgba(148, 163, 184, 0.3); color: #94a3b8; font-size: 0.85rem;">
                    <i class="bi bi-people me-1"></i> {{ $agentesTotal }} Agentes
                </span>
            </div>
        </div>
    </div>

    <!-- Barra de Control y Acciones Masivas -->
    <form method="POST" action="{{ route('admin.roles.mass') }}" id="formRolesMasivo">
        @csrf
        <input type="hidden" name="accion" value="" id="accionMasiva">

        <div class="roles-control-bar d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="form-check mb-0 d-flex align-items-center gap-2">
                    <input type="checkbox" id="seleccionarTodosRoles" class="form-check-input ms-0 shadow-sm" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                    <label class="form-check-label fw-bold text-secondary" for="seleccionarTodosRoles" style="cursor: pointer; user-select: none; font-size: 0.88rem;">Seleccionar todos</label>
                </div>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <button type="button" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCrearRol" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: 0; font-size: 0.88rem;">
                    <i class="bi bi-plus-circle-fill"></i> Añadir Nuevo Rol
                </button>
                
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle px-3 py-2 rounded-3 fw-bold d-flex align-items-center gap-2" type="button" id="dropdownAccionesRoles" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.88rem; background: rgba(255,255,255,0.05); color: #cbd5e1; border-color: rgba(255,255,255,0.12);">
                        <i class="bi bi-gear-fill"></i> Acciones
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" aria-labelledby="dropdownAccionesRoles" style="background: #1e242c; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">
                        <li><button class="dropdown-item fw-semibold text-white rounded-3 py-2" type="button" data-accion-rol="habilitar"><i class="bi bi-check-circle-fill text-success me-2"></i>Habilitar</button></li>
                        <li><button class="dropdown-item fw-semibold text-white rounded-3 py-2" type="button" data-accion-rol="deshabilitar"><i class="bi bi-slash-circle-fill text-warning me-2"></i>Deshabilitar</button></li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li><button class="dropdown-item fw-semibold text-danger rounded-3 py-2" type="button" data-accion-rol="eliminar"><i class="bi bi-trash-fill me-2"></i>Eliminar</button></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabla de Roles -->
        <div class="roles-table-wrapper">
            <div class="table-responsive">
                <table class="roles-table">
                    <thead>
                        <tr>
                            <th width="40" class="text-center d-none d-md-table-cell">#</th>
                            <th class="d-none d-md-table-cell">Rol</th>
                            <th class="d-none d-md-table-cell">Estado</th>
                            <th class="d-none d-md-table-cell">Creado en</th>
                            <th class="d-none d-md-table-cell">Última Actualización</th>
                            <th class="text-center d-none d-md-table-cell">Agentes</th>
                            <th class="text-center d-none d-md-table-cell">Activos</th>
                            <th width="140" class="text-end d-none d-md-table-cell">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $rol)
                            <tr data-rol-id="{{ $rol->id }}" style="cursor: pointer;">
                                <!-- Vista Móvil -->
                                <td class="d-md-none p-0">
                                    <div class="role-mobile-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="checkbox" name="ids[]" value="{{ $rol->id }}" class="form-check-input rol-checkbox m-0 shadow-sm" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                                                @if($rol->is_enabled)
                                                    <span class="role-badge active"><i class="bi bi-check-circle-fill"></i> Activo</span>
                                                @else
                                                    <span class="role-badge inactive"><i class="bi bi-pause-circle-fill"></i> Inactivo</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('admin.roles.permissions', ['nombre' => $rol->name]) }}" class="btn-permissions p-2" title="Permisos">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </div>

                                        <div class="fw-bold fs-5 text-white mb-1">
                                            <a href="{{ route('admin.roles.permissions', ['nombre' => $rol->name]) }}" class="text-white text-decoration-none">
                                                {{ $rol->name }}
                                            </a>
                                        </div>

                                        <div class="text-secondary small mb-3">
                                            <i class="bi bi-calendar3 me-1"></i> Creado: {{ $rol->created ? $rol->created->format('d/m/Y') : '-' }}
                                        </div>

                                        <div class="row g-2 pt-2 border-top border-secondary border-opacity-25">
                                            <div class="col-6">
                                                <div class="p-2 rounded bg-dark border border-secondary border-opacity-25 text-center">
                                                    <div class="text-secondary small fw-bold text-uppercase">Total Agentes</div>
                                                    <div class="fs-5 fw-bold text-white">{{ $rol->total_agentes }}</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 rounded bg-dark border border-secondary border-opacity-25 text-center">
                                                    <div class="text-secondary small fw-bold text-uppercase">Activos</div>
                                                    <div class="fs-5 fw-bold text-success">{{ $rol->total_activos }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Vista Escritorio -->
                                <td class="text-center d-none d-md-table-cell" onclick="event.stopPropagation();">
                                    <input type="checkbox" name="ids[]" value="{{ $rol->id }}" class="form-check-input rol-checkbox shadow-sm m-0" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fs-5 {{ $rol->is_enabled ? 'text-primary' : 'text-secondary' }}"><i class="bi bi-shield-lock-fill"></i></span>
                                        <a href="{{ route('admin.roles.permissions', ['nombre' => $rol->name]) }}" class="text-decoration-none fw-bold text-white fs-6">
                                            {{ $rol->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    @if($rol->is_enabled)
                                        <span class="role-badge active"><i class="bi bi-check-circle-fill"></i> Activo</span>
                                    @else
                                        <span class="role-badge inactive"><i class="bi bi-pause-circle-fill"></i> Inactivo</span>
                                    @endif
                                </td>
                                <td class="d-none d-md-table-cell text-secondary">
                                    <i class="bi bi-calendar3 me-1"></i> {{ $rol->created ? $rol->created->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="d-none d-md-table-cell text-secondary">
                                    <i class="bi bi-arrow-clockwise me-1"></i> {{ $rol->updated ? $rol->updated->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="text-center d-none d-md-table-cell">
                                    <span class="agent-count-badge">{{ $rol->total_agentes }}</span>
                                </td>
                                <td class="text-center d-none d-md-table-cell">
                                    <span class="agent-count-badge {{ $rol->total_activos > 0 ? 'active-agents' : '' }}">{{ $rol->total_activos }}</span>
                                </td>
                                <td class="text-end d-none d-md-table-cell" onclick="event.stopPropagation();">
                                    <a href="{{ route('admin.roles.permissions', ['nombre' => $rol->name]) }}" class="btn-permissions">
                                        <i class="bi bi-sliders"></i> Permisos
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-secondary py-5">
                                    <span class="fs-2 mb-2 d-block"><i class="bi bi-shield-slash"></i></span>
                                    No se encontraron roles registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- Modal para Añadir Nuevo Rol -->
    <x-agent.modal 
        id="modalCrearRol" 
        titulo="Añadir Nuevo Rol" 
        subtitulo="Crea un nuevo rol de permisos para el personal" 
        icono="bi-shield-plus" 
        colorIcono="text-primary" 
        :formAction="route('admin.roles.store')" 
        metodo="POST" 
        textoBoton="Guardar Rol" 
        colorBoton="btn-primary" 
        iconoBoton="bi-check-circle"
        tamano="modal-md"
    >
        <div class="mb-3">
            <label for="name" class="form-label text-white fw-semibold">Nombre del Rol <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" class="form-control bg-dark border-secondary text-white" placeholder="Ej. Supervisor de Soporte" required autocomplete="off">
            <div class="form-text text-secondary" style="font-size: 0.8rem;">
                El nombre identificará el rol en la asignación de permisos y moderadores.
            </div>
        </div>
    </x-agent.modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('seleccionarTodosRoles');
            const checkboxes = document.querySelectorAll('.rol-checkbox');

            function syncRowSelection(cb) {
                const row = cb.closest('tr');
                if (row) {
                    row.classList.toggle('selected', cb.checked);
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => {
                        cb.checked = selectAll.checked;
                        syncRowSelection(cb);
                    });
                });
            }

            checkboxes.forEach(cb => {
                syncRowSelection(cb);
                cb.addEventListener('change', function() {
                    syncRowSelection(cb);
                });

                // Seleccionar al hacer clic en la fila (excepto en links/botones)
                const row = cb.closest('tr');
                if (row) {
                    row.addEventListener('click', function(e) {
                        if (e.target.closest('a') || e.target.closest('button') || e.target === cb || e.target.closest('.rol-checkbox')) {
                            return;
                        }
                        cb.checked = !cb.checked;
                        cb.dispatchEvent(new Event('change'));
                    });
                }
            });

            // Botones de acciones masivas
            const actionButtons = document.querySelectorAll('[data-accion-rol]');
            const form = document.getElementById('formRolesMasivo');
            const hiddenAccion = document.getElementById('accionMasiva');

            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const checked = document.querySelectorAll('.rol-checkbox:checked');
                    if (checked.length === 0) {
                        alert('Debe seleccionar al menos un rol.');
                        return;
                    }

                    const accion = this.getAttribute('data-accion-rol');
                    if (accion === 'eliminar') {
                        if (!confirm('¿Estás seguro de que deseas eliminar los roles seleccionados?')) {
                            return;
                        }
                    }

                    hiddenAccion.value = accion;
                    form.submit();
                });
            });
        });
    </script>
    @endpush
</x-agent.admin-layout>

