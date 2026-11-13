<x-agent.admin-layout>
    @push('styles')
    <style>
        .topics-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .topics-hero-icon {
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

        .topics-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .topics-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .topics-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .topics-table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            font-size: 0.88rem;
        }

        .topics-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .badge-active {
            background-color: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .badge-inactive {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-public {
            background-color: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .badge-internal {
            background-color: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
    </style>
    @endpush

    <!-- Header Hero -->
    <div class="topics-hero">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="topics-hero-icon">
                    <i class="bi bi-list-check"></i>
                </div>
                <div>
                    <h1 class="h4 text-white fw-bold mb-1">Temas de Ayuda</h1>
                    <p class="text-muted small mb-0">
                        Clasificación y enrutamiento automático de solicitudes por departamento técnico.
                    </p>
                </div>
            </div>
            <button type="button" class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 fw-semibold" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: none; border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#crearTemaModal">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Nuevo Tema</span>
            </button>
        </div>
    </div>

    <!-- Alertas Flash -->
    @if(session('success'))
        <div class="alert alert-success bg-dark text-success border border-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning bg-dark text-warning border border-warning d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('warning') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger bg-dark text-danger border border-danger d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-x-circle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Tarjetas de Métricas -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(14, 165, 233, 0.15); color: #0ea5e9;">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">TOTAL TEMAS</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(34, 197, 94, 0.15); color: #22c55e;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">ACTIVOS</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['activos'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                    <i class="bi bi-globe"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">PÚBLICOS (CLIENTES)</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['publicos'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-bold">SOLO INTERNOS</div>
                    <div class="text-white fs-4 fw-bold">{{ $estadisticas['internos'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Búsqueda y Filtros -->
    <div class="control-bar">
        <form method="GET" action="{{ route('admin.helptopics') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-black border-secondary text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="q" value="{{ $busqueda }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Buscar por tema o descripción...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="dept_id" class="form-select form-select-sm bg-black text-white border-secondary shadow-none" onchange="this.form.submit()">
                    <option value="0">Todos los departamentos</option>
                    @foreach($departamentos as $dept)
                        <option value="{{ $dept->id }}" {{ $filtroDept === $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="estado" class="form-select form-select-sm bg-black text-white border-secondary shadow-none" onchange="this.form.submit()">
                    <option value="todos" {{ $filtroEstado === 'todos' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="activos" {{ $filtroEstado === 'activos' ? 'selected' : '' }}>Solo Activos</option>
                    <option value="inactivos" {{ $filtroEstado === 'inactivos' ? 'selected' : '' }}>Solo Inactivos</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="visibilidad" class="form-select form-select-sm bg-black text-white border-secondary shadow-none" onchange="this.form.submit()">
                    <option value="todos" {{ $filtroVisibilidad === 'todos' ? 'selected' : '' }}>Toda visibilidad</option>
                    <option value="publicos" {{ $filtroVisibilidad === 'publicos' ? 'selected' : '' }}>Públicos (Clientes)</option>
                    <option value="internos" {{ $filtroVisibilidad === 'internos' ? 'selected' : '' }}>Solo Internos</option>
                </select>
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-outline-light w-100" title="Filtrar">
                    <i class="bi bi-funnel"></i>
                </button>
                @if(!empty($busqueda) || $filtroDept > 0 || $filtroEstado !== 'todos' || $filtroVisibilidad !== 'todos')
                    <a href="{{ route('admin.helptopics') }}" class="btn btn-sm btn-dark text-muted" title="Limpiar filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Formulario para Acciones Masivas -->
    <form action="{{ route('admin.helptopics.mass') }}" method="POST" id="formAccionesMasivas">
        @csrf

        <!-- Barra de Acciones Masivas -->
        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
            <div class="d-flex align-items-center gap-2">
                <select name="accion" class="form-select form-select-sm bg-dark text-white border-secondary shadow-none" style="width: 220px;" required>
                    <option value="">Acciones en lote...</option>
                    <option value="habilitar">Habilitar seleccionados</option>
                    <option value="deshabilitar">Deshabilitar seleccionados</option>
                    <option value="hacer_publico">Hacer visibles para clientes</option>
                    <option value="hacer_privado">Hacer solo uso interno</option>
                    <option value="eliminar">Eliminar seleccionados</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="return confirm('¿Deseas aplicar la acción seleccionada a los temas marcados?')">
                    Aplicar
                </button>
            </div>
            <div class="text-muted small">
                Total mostrados: <strong class="text-white">{{ $temas->count() }}</strong>
            </div>
        </div>

        <!-- Tabla de Temas de Ayuda -->
        <div class="topics-table-wrapper mb-4">
            <div class="table-responsive">
                <table class="topics-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;" class="text-center">
                                <input type="checkbox" id="checkTodos" class="form-check-input bg-black border-secondary">
                            </th>
                            <th>Tema de Ayuda</th>
                            <th>Departamento Asignado</th>
                            <th style="width: 140px;">Visibilidad</th>
                            <th style="width: 120px;">Estado</th>
                            <th style="width: 110px;" class="text-center">Tickets</th>
                            <th style="width: 130px;" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($temas as $tema)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $tema->id }}" class="form-check-input bg-black border-secondary check-item">
                                </td>
                                <td>
                                    <div class="fw-semibold text-white d-flex align-items-center gap-2">
                                        <i class="bi bi-tag text-info"></i>
                                        <span>{{ $tema->name }}</span>
                                    </div>
                                    @if($tema->description)
                                        <div class="text-muted small text-truncate mt-1" style="max-width: 380px;" title="{{ $tema->description }}">
                                            {{ $tema->description }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($tema->departamento)
                                        <span class="badge rounded-pill bg-dark border border-secondary text-light px-2 py-1">
                                            <i class="bi bi-building me-1 opacity-75"></i> {{ $tema->departamento->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Sin asignar</span>
                                    @endif
                                </td>
                                <td>
                                    @if($tema->is_public)
                                        <span class="badge rounded-pill badge-public px-2 py-1 small">
                                            <i class="bi bi-globe me-1"></i> Público
                                        </span>
                                    @else
                                        <span class="badge rounded-pill badge-internal px-2 py-1 small">
                                            <i class="bi bi-lock me-1"></i> Interno
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.helptopics.toggle', $tema->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="border-0 bg-transparent p-0" title="Clic para alternar estado">
                                            @if($tema->is_active)
                                                <span class="badge rounded-pill badge-active px-2 py-1 small" style="cursor: pointer;">
                                                    <i class="bi bi-check-circle me-1"></i> Activo
                                                </span>
                                            @else
                                                <span class="badge rounded-pill badge-inactive px-2 py-1 small" style="cursor: pointer;">
                                                    <i class="bi bi-x-circle me-1"></i> Inactivo
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <span class="badge rounded-pill bg-dark text-muted border border-secondary px-2 py-1">
                                        {{ $tema->total_tickets }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Editar -->
                                        <button type="button" class="btn btn-action btn-outline-light" data-bs-toggle="modal" data-bs-target="#editarTemaModal{{ $tema->id }}" title="Editar tema">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <!-- Eliminar -->
                                        <button type="button" class="btn btn-action btn-outline-danger" data-bs-toggle="modal" data-bs-target="#eliminarTemaModal{{ $tema->id }}" title="Eliminar tema">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-tags fs-1 d-block mb-2 opacity-50"></i>
                                    @if(!empty($busqueda) || $filtroDept > 0 || $filtroEstado !== 'todos' || $filtroVisibilidad !== 'todos')
                                        No se encontraron temas de ayuda que coincidan con los filtros aplicados.
                                    @else
                                        No hay temas de ayuda registrados aún. ¡Crea el primero haciendo clic en "Nuevo Tema"!
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- Modales de Edición y Eliminación para cada Tema -->
    @foreach($temas as $tema)
        <!-- Modal Editar Tema -->
        <x-agent.modal id="editarTemaModal{{ $tema->id }}" title="Editar Tema: {{ $tema->name }}" size="modal-lg">
            <form action="{{ route('admin.helptopics.update', $tema->id) }}" method="POST" id="formEditarTema{{ $tema->id }}">
                @csrf
                @method('PUT')
                <div class="row g-3 text-start">
                    <div class="col-md-7">
                        <label class="form-label text-white small fw-bold">Nombre del Tema <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $tema->name) }}" class="form-control bg-black text-white border-secondary shadow-none" required maxlength="100">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label text-white small fw-bold">Departamento Asignado <span class="text-danger">*</span></label>
                        <select name="dept_id" class="form-select bg-black text-white border-secondary shadow-none" required>
                            @foreach($departamentos as $dept)
                                <option value="{{ $dept->id }}" {{ old('dept_id', $tema->dept_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold">Descripción / Instrucciones</label>
                        <textarea name="description" class="form-control bg-black text-white border-secondary shadow-none" rows="3" placeholder="Descripción breve del tema o qué tipo de solicitudes abarca...">{{ old('description', $tema->description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch p-3 rounded bg-black border border-secondary">
                            <input class="form-check-input" type="checkbox" name="is_active" id="checkActivo{{ $tema->id }}" value="1" {{ old('is_active', $tema->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label text-white small ms-2" for="checkActivo{{ $tema->id }}">
                                <strong>Tema Activo</strong>
                                <span class="d-block text-muted" style="font-size: 0.78rem;">Habilita el tema para la recepción de solicitudes.</span>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch p-3 rounded bg-black border border-secondary">
                            <input class="form-check-input" type="checkbox" name="is_public" id="checkPublico{{ $tema->id }}" value="1" {{ old('is_public', $tema->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label text-white small ms-2" for="checkPublico{{ $tema->id }}">
                                <strong>Visible para Clientes</strong>
                                <span class="d-block text-muted" style="font-size: 0.78rem;">Se mostrará en el formulario de creación de tickets del portal web.</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
            <x-slot:footer>
                <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formEditarTema{{ $tema->id }}" class="btn btn-primary fw-semibold px-4">
                    Guardar Cambios
                </button>
            </x-slot:footer>
        </x-agent.modal>

        <!-- Modal Eliminar Tema -->
        <x-delete-modal 
            id="eliminarTemaModal{{ $tema->id }}" 
            title="Eliminar Tema: {{ $tema->name }}" 
            message="¿Estás seguro de que deseas eliminar este tema de ayuda? Si tiene tickets asociados el sistema impedirá su borrado para proteger la integridad histórica."
            formAction="{{ route('admin.helptopics.destroy', $tema->id) }}"
            submitText="Eliminar Tema"
        />
    @endforeach

    <!-- Modal Crear Nuevo Tema -->
    <x-agent.modal id="crearTemaModal" title="Nuevo Tema de Ayuda" size="modal-lg">
        <form action="{{ route('admin.helptopics.store') }}" method="POST" id="formCrearTema">
            @csrf
            <div class="row g-3 text-start">
                <div class="col-md-7">
                    <label class="form-label text-white small fw-bold">Nombre del Tema <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control bg-black text-white border-secondary shadow-none" placeholder="Ej: Soporte de Redes, Facturación, Reparación..." required maxlength="100">
                </div>
                <div class="col-md-5">
                    <label class="form-label text-white small fw-bold">Departamento Asignado <span class="text-danger">*</span></label>
                    <select name="dept_id" class="form-select bg-black text-white border-secondary shadow-none" required>
                        <option value="">Selecciona un departamento...</option>
                        @foreach($departamentos as $dept)
                            <option value="{{ $dept->id }}" {{ old('dept_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label text-white small fw-bold">Descripción / Instrucciones</label>
                    <textarea name="description" class="form-control bg-black text-white border-secondary shadow-none" rows="3" placeholder="Describe brevemente de qué trata este tema o qué problemas resuelve...">{{ old('description') }}</textarea>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch p-3 rounded bg-black border border-secondary">
                        <input class="form-check-input" type="checkbox" name="is_active" id="checkNuevoActivo" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-white small ms-2" for="checkNuevoActivo">
                            <strong>Tema Activo</strong>
                            <span class="d-block text-muted" style="font-size: 0.78rem;">Habilita el tema para su uso inmediato.</span>
                        </label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch p-3 rounded bg-black border border-secondary">
                        <input class="form-check-input" type="checkbox" name="is_public" id="checkNuevoPublico" value="1" {{ old('is_public', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label text-white small ms-2" for="checkNuevoPublico">
                            <strong>Visible para Clientes</strong>
                            <span class="d-block text-muted" style="font-size: 0.78rem;">Disponible en el formulario de creación de tickets para usuarios.</span>
                        </label>
                    </div>
                </div>
            </div>
        </form>
        <x-slot:footer>
            <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" form="formCrearTema" class="btn btn-primary fw-semibold px-4" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: none;">
                Crear Tema
            </button>
        </x-slot:footer>
    </x-agent.modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkTodos = document.getElementById('checkTodos');
            const checkboxes = document.querySelectorAll('.check-item');

            if (checkTodos) {
                checkTodos.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = checkTodos.checked);
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    if (!this.checked && checkTodos) {
                        checkTodos.checked = false;
                    } else if (checkTodos) {
                        const todosMarcados = Array.from(checkboxes).every(c => c.checked);
                        checkTodos.checked = todosMarcados;
                    }
                });
            });
        });
    </script>
    @endpush
</x-agent.admin-layout>

