<x-agent.admin-layout>
    @push('styles')
    <style>
        .depts-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .depts-hero-icon {
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

        .depts-control-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .depts-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .depts-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .depts-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .depts-table td {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .depts-table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .depts-table tbody tr.selected td {
            background-color: rgba(37, 99, 235, 0.08);
        }

        .dept-badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .dept-badge.active {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .dept-badge.inactive {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.25);
        }

        .count-badge {
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

        .count-badge.has-tickets {
            background: rgba(59, 130, 246, 0.18);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .count-badge.has-agents {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .btn-edit-dept {
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
            cursor: pointer;
        }

        .btn-edit-dept:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* Tarjetas móviles */
        .dept-mobile-card {
            padding: 16px;
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            margin-bottom: 12px;
        }

        .dept-mobile-card-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .dept-mobile-card-meta {
            color: #94a3b8;
            font-size: 0.82rem;
            line-height: 1.4;
        }

        .dept-stat-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
        }

        .dept-stat-box .stat-label {
            font-size: 0.72rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .dept-stat-box .stat-value {
            font-size: 1.15rem;
            font-weight: 700;
            color: #ffffff;
        }

        @media (max-width: 768px) {
            .depts-table-wrapper {
                background: transparent;
                border: none;
                box-shadow: none;
            }
        }
    </style>
    @endpush

    <!-- Hero Card con Estadísticas -->
    <div class="depts-hero">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <span class="depts-hero-icon"><i class="bi bi-diagram-3"></i></span>
                <div>
                    <h1 class="h4 text-white fw-bold mb-1">Departamentos</h1>
                    <p class="text-secondary mb-0" style="font-size: 0.9rem;">Estructura organizativa y niveles de escalamiento del soporte</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold border border-success-subtle">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ $conteoActivos }} Activos
                </span>
                <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill fw-bold border border-secondary-subtle">
                    <i class="bi bi-pause-circle-fill me-1"></i> {{ $conteoInactivos }} Inactivos
                </span>
                <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill fw-bold border border-info-subtle">
                    <i class="bi bi-diagram-3-fill me-1"></i> {{ $totalDepartamentos }} Total
                </span>
            </div>
        </div>
    </div>

    <!-- Barra de Control y Acciones Masivas -->
    <form method="POST" action="{{ route('admin.departments.mass') }}" id="formDeptsMasivo">
        @csrf
        <input type="hidden" name="accion" id="accionMasiva" value="">

        <div class="depts-control-bar d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <div class="form-check mb-0 d-flex align-items-center gap-2">
                    <input type="checkbox" id="seleccionarTodosDepts" class="form-check-input ms-0" style="width: 1.25rem; height: 1.25rem; cursor: pointer; border-radius: 4px;">
                    <label class="form-check-label fw-bold text-secondary" for="seleccionarTodosDepts" style="cursor: pointer; user-select: none; font-size: 0.9rem;">
                        Seleccionar todos
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2 align-items-center">
                <button type="button" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCrearDepartamento" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: 0; font-size: 0.88rem;">
                    <i class="bi bi-plus-circle-fill"></i> Añadir nuevo Departamento
                </button>
                
                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle px-3 py-2 rounded-3 fw-bold d-flex align-items-center gap-2 border border-secondary" type="button" id="deptsAccionesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.88rem; background-color: #21262d;">
                        <i class="bi bi-gear-fill"></i> Acciones
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" aria-labelledby="deptsAccionesDropdown" style="background-color: #16191d; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px;">
                        <li>
                            <button class="dropdown-item fw-semibold rounded-3 py-2 text-white" type="button" data-accion-dept="habilitar">
                                <i class="bi bi-check-circle-fill text-success me-2"></i> Habilitar
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item fw-semibold rounded-3 py-2 text-white" type="button" data-accion-dept="deshabilitar">
                                <i class="bi bi-slash-circle-fill text-warning me-2"></i> Deshabilitar
                            </button>
                        </li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li>
                            <button class="dropdown-item fw-semibold rounded-3 py-2 text-danger" type="button" data-accion-dept="eliminar">
                                <i class="bi bi-trash-fill me-2"></i> Eliminar
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabla y Tarjetas de Departamentos -->
        <div class="depts-table-wrapper">
            <div class="table-responsive">
                <table class="depts-table">
                    <thead>
                        <tr>
                            <th width="50" class="text-center d-none d-md-table-cell">#</th>
                            <th class="d-none d-md-table-cell">Departamento</th>
                            <th class="text-center d-none d-md-table-cell">Estado</th>
                            <th class="text-center d-none d-md-table-cell">Agentes</th>
                            <th class="text-center d-none d-md-table-cell">Tickets</th>
                            <th class="text-center d-none d-md-table-cell">Reporte Cierre</th>
                            <th width="140" class="text-end d-none d-md-table-cell">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departamentos as $departamento)
                            <tr data-dept-id="{{ $departamento->id }}" style="cursor: pointer;">
                                <!-- VISTA MÓVIL (Tarjeta) -->
                                <td class="d-md-none p-0">
                                    <div class="dept-mobile-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <input type="checkbox" name="ids[]" value="{{ $departamento->id }}" class="form-check-input dept-checkbox m-0 shadow-sm" style="width: 1.25rem; height: 1.25rem; cursor: pointer; border-radius: 4px;">
                                                @if ($departamento->is_active)
                                                    <span class="dept-badge active"><i class="bi bi-check-circle-fill"></i> Activo</span>
                                                @else
                                                    <span class="dept-badge inactive"><i class="bi bi-pause-circle-fill"></i> Inactivo</span>
                                                @endif
                                            </div>
                                            <button type="button" class="btn-edit-dept btnEditarDept"
                                                data-id="{{ $departamento->id }}"
                                                data-name="{{ $departamento->name }}"
                                                data-description="{{ $departamento->description }}"
                                                data-is-active="{{ $departamento->is_active ? '1' : '0' }}"
                                                data-requires-report="{{ $departamento->requires_report ? '1' : '0' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarDepartamento"
                                                title="Editar departamento">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </div>

                                        <div class="dept-mobile-card-title">
                                            {{ $departamento->name }}
                                        </div>

                                        @if ($departamento->description)
                                            <div class="dept-mobile-card-meta mb-2">
                                                {{ $departamento->description }}
                                            </div>
                                        @endif

                                        @if ($departamento->cuentaCorreo)
                                            <div class="dept-mobile-card-meta mb-2">
                                                <i class="bi bi-envelope text-secondary me-1"></i>
                                                <span class="text-info">{{ $departamento->cuentaCorreo->email }}</span>
                                            </div>
                                        @endif

                                        <div class="row g-2 mt-2 pt-2">
                                            <div class="col-4">
                                                <div class="dept-stat-box">
                                                    <div class="stat-label">Agentes</div>
                                                    <div class="stat-value text-success">{{ $departamento->total_agentes }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="dept-stat-box">
                                                    <div class="stat-label">Tickets</div>
                                                    <div class="stat-value text-primary">{{ $departamento->total_tickets }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="dept-stat-box">
                                                    <div class="stat-label">Reporte</div>
                                                    <div class="stat-value" style="font-size: 0.9rem;">
                                                        {{ $departamento->requires_report ? 'Oblig.' : 'Opc.' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- VISTA ESCRITORIO -->
                                <td class="text-center d-none d-md-table-cell" onclick="event.stopPropagation();">
                                    <input type="checkbox" name="ids[]" value="{{ $departamento->id }}" class="form-check-input dept-checkbox shadow-sm m-0" style="width: 1.25rem; height: 1.25rem; cursor: pointer; border-radius: 4px;">
                                </td>
                                
                                <td class="d-none d-md-table-cell">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="fs-4" style="color: {{ $departamento->is_active ? '#3b82f6' : '#64748b' }};">
                                            <i class="bi bi-diagram-3-fill"></i>
                                        </span>
                                        <div>
                                            <div class="fw-bold text-white fs-6">
                                                {{ $departamento->name }}
                                            </div>
                                            @if ($departamento->description)
                                                <div class="text-secondary small" style="font-size: 0.8rem; line-height: 1.3;">
                                                    {{ $departamento->description }}
                                                </div>
                                            @endif
                                            @if ($departamento->cuentaCorreo)
                                                <div class="text-secondary small mt-1" style="font-size: 0.78rem;">
                                                    <i class="bi bi-envelope text-info me-1"></i>
                                                    <span class="text-info">{{ $departamento->cuentaCorreo->email }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center d-none d-md-table-cell">
                                    @if ($departamento->is_active)
                                        <span class="dept-badge active"><i class="bi bi-check-circle-fill"></i> Activo</span>
                                    @else
                                        <span class="dept-badge inactive"><i class="bi bi-pause-circle-fill"></i> Inactivo</span>
                                    @endif
                                </td>

                                <td class="text-center d-none d-md-table-cell">
                                    <span class="count-badge {{ $departamento->total_agentes > 0 ? 'has-agents' : '' }}">
                                        {{ $departamento->total_agentes }}
                                    </span>
                                </td>

                                <td class="text-center d-none d-md-table-cell">
                                    <span class="count-badge {{ $departamento->total_tickets > 0 ? 'has-tickets' : '' }}">
                                        {{ $departamento->total_tickets }}
                                    </span>
                                </td>

                                <td class="text-center d-none d-md-table-cell">
                                    @if ($departamento->requires_report)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded" style="font-size: 0.75rem;">
                                            <i class="bi bi-file-earmark-check me-1"></i> Obligatorio
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-2 py-1 rounded" style="font-size: 0.75rem;">
                                            Opcional
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end d-none d-md-table-cell" onclick="event.stopPropagation();">
                                    <button type="button" class="btn-edit-dept btnEditarDept"
                                        data-id="{{ $departamento->id }}"
                                        data-name="{{ $departamento->name }}"
                                        data-description="{{ $departamento->description }}"
                                        data-is-active="{{ $departamento->is_active ? '1' : '0' }}"
                                        data-requires-report="{{ $departamento->requires_report ? '1' : '0' }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarDepartamento">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary py-5">
                                    <span class="fs-2 mb-2 d-block"><i class="bi bi-diagram-3"></i></span>
                                    No se encontraron departamentos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- Modal para Añadir Nuevo Departamento -->
    <x-agent.modal 
        id="modalCrearDepartamento" 
        titulo="Añadir Nuevo Departamento" 
        subtitulo="Crea un nuevo departamento para el enrutamiento de tickets y personal" 
        icono="bi-diagram-3-fill" 
        colorIcono="text-primary" 
        :formAction="route('admin.departments.store')" 
        metodo="POST" 
        textoBoton="Guardar Departamento" 
        colorBoton="btn-primary" 
        iconoBoton="bi-check-circle"
        tamano="modal-md"
    >
        <div class="mb-3">
            <label for="create_dept_name" class="form-label text-white fw-semibold">Nombre del Departamento <span class="text-danger">*</span></label>
            <input type="text" name="name" id="create_dept_name" class="form-control bg-dark border-secondary text-white" placeholder="Ej. Soporte Técnico" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label for="create_dept_description" class="form-label text-white fw-semibold">Descripción</label>
            <textarea name="description" id="create_dept_description" rows="3" class="form-control bg-dark border-secondary text-white" placeholder="Descripción breve del departamento o área..."></textarea>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="create_dept_is_active" value="1" checked>
            <label class="form-check-label text-white fw-semibold" for="create_dept_is_active">
                Departamento Activo
            </label>
            <div class="form-text text-secondary" style="font-size: 0.8rem;">
                Permite la recepción y asignación de tickets a este departamento.
            </div>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="requires_report" id="create_dept_requires_report" value="1">
            <label class="form-check-label text-white fw-semibold" for="create_dept_requires_report">
                Requiere reporte de cierre
            </label>
            <div class="form-text text-secondary" style="font-size: 0.8rem;">
                Obliga al agente a completar la hoja de reporte antes de cerrar un ticket.
            </div>
        </div>
    </x-agent.modal>

    <!-- Modal para Editar Departamento -->
    <x-agent.modal 
        id="modalEditarDepartamento" 
        titulo="Editar Departamento" 
        subtitulo="Modifica la información y estado del departamento" 
        icono="bi-pencil-square" 
        colorIcono="text-primary" 
        accion="#" 
        metodo="PUT" 
        textoBoton="Guardar Cambios" 
        colorBoton="btn-primary" 
        iconoBoton="bi-check-circle"
        tamano="modal-md"
    >
        <input type="hidden" name="id" id="edit_dept_id" value="">

        <div class="mb-3">
            <label for="edit_dept_name" class="form-label text-white fw-semibold">Nombre del Departamento <span class="text-danger">*</span></label>
            <input type="text" name="name" id="edit_dept_name" class="form-control bg-dark border-secondary text-white" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label for="edit_dept_description" class="form-label text-white fw-semibold">Descripción</label>
            <textarea name="description" id="edit_dept_description" rows="3" class="form-control bg-dark border-secondary text-white"></textarea>
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="edit_dept_is_active" value="1">
            <label class="form-check-label text-white fw-semibold" for="edit_dept_is_active">
                Departamento Activo
            </label>
            <div class="form-text text-secondary" style="font-size: 0.8rem;">
                Permite la recepción y asignación de tickets a este departamento.
            </div>
        </div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="requires_report" id="edit_dept_requires_report" value="1">
            <label class="form-check-label text-white fw-semibold" for="edit_dept_requires_report">
                Requiere reporte de cierre
            </label>
            <div class="form-text text-secondary" style="font-size: 0.8rem;">
                Obliga al agente a completar la hoja de reporte antes de cerrar un ticket.
            </div>
        </div>
    </x-agent.modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('seleccionarTodosDepts');
            const checkboxes = document.querySelectorAll('.dept-checkbox');

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

                // Seleccionar al hacer clic en la fila
                const row = cb.closest('tr');
                if (row) {
                    row.addEventListener('click', function(e) {
                        if (e.target.closest('a') || e.target.closest('button') || e.target === cb || e.target.closest('.dept-checkbox')) {
                            return;
                        }
                        cb.checked = !cb.checked;
                        cb.dispatchEvent(new Event('change'));
                    });
                }
            });

            // Poblar modal de edición
            const modalEditar = document.getElementById('modalEditarDepartamento');
            const formEditar = modalEditar ? modalEditar.querySelector('form') : null;
            const inputId = document.getElementById('edit_dept_id');
            const inputName = document.getElementById('edit_dept_name');
            const inputDesc = document.getElementById('edit_dept_description');
            const checkActive = document.getElementById('edit_dept_is_active');
            const checkRequiresReport = document.getElementById('edit_dept_requires_report');

            document.querySelectorAll('.btnEditarDept').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name') || '';
                    const desc = this.getAttribute('data-description') || '';
                    const isActive = this.getAttribute('data-is-active') === '1';
                    const requiresReport = this.getAttribute('data-requires-report') === '1';

                    if (inputId) inputId.value = id;
                    if (inputName) inputName.value = name;
                    if (inputDesc) inputDesc.value = desc;
                    if (checkActive) checkActive.checked = isActive;
                    if (checkRequiresReport) checkRequiresReport.checked = requiresReport;

                    if (formEditar) {
                        formEditar.action = `/admin/departments/${id}`;
                    }
                });
            });

            // Botones de acciones masivas
            const actionButtons = document.querySelectorAll('[data-accion-dept]');
            const formMasivo = document.getElementById('formDeptsMasivo');
            const hiddenAccion = document.getElementById('accionMasiva');

            actionButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const checked = document.querySelectorAll('.dept-checkbox:checked');
                    if (checked.length === 0) {
                        alert('Debe seleccionar al menos un departamento.');
                        return;
                    }

                    const accion = this.getAttribute('data-accion-dept');
                    if (accion === 'eliminar') {
                        if (!confirm('¿Estás seguro de que deseas eliminar los departamentos seleccionados? Solo se eliminarán aquellos que no tengan agentes, tickets ni temas asignados.')) {
                            return;
                        }
                    }

                    hiddenAccion.value = accion;
                    formMasivo.submit();
                });
            });
        });
    </script>
    @endpush
</x-agent.admin-layout>

