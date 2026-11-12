<x-agent.admin-layout>
    @push('styles')
    <style>
        .seq-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .seq-hero-icon {
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

        .seq-control-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .seq-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .seq-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .seq-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .seq-table td {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .seq-table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="seq-hero">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="seq-hero-icon">
                    <i class="bi bi-123"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Secuencias Numéricas</h1>
                    <p class="text-secondary small mb-0">Controla los contadores correlativos, rellenos de ceros y saltos para la numeración consecutiva de tickets.</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.settings.tickets') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold small">
                    <i class="bi bi-arrow-left me-1"></i> Configuración de Solicitudes
                </a>
                <button type="button" class="btn btn-danger rounded-pill px-3 py-2 fw-semibold shadow-sm small" style="background-color: #ef4444; border-color: #ef4444;" data-bs-toggle="modal" data-bs-target="#modalNuevaSecuencia">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Secuencia
                </button>
            </div>
        </div>
    </div>

    <!-- Barra de Control -->
    <div class="seq-control-bar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2 text-secondary">
                <i class="bi bi-list-ol me-1"></i> Secuencias Registradas
            </span>
        </div>
        <div class="text-secondary small fw-semibold">
            Total de secuencias: <strong class="text-white">{{ $secuencias->count() }}</strong>
        </div>
    </div>

    <!-- Tabla de Secuencias -->
    <div class="seq-table-wrapper">
        @if($secuencias->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-hash fs-1 mb-2 d-block opacity-50"></i>
                <h5 class="fw-bold text-white">No hay secuencias numéricas registradas</h5>
                <p class="small text-secondary mb-3">Crea una secuencia para establecer contadores automáticos para tickets.</p>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNuevaSecuencia">
                    Crear primera secuencia
                </button>
            </div>
        @else
            <table class="seq-table">
                <thead>
                    <tr>
                        <th>Nombre de la Secuencia</th>
                        <th>Próximo Valor</th>
                        <th>Incremento</th>
                        <th>Relleno (Padding)</th>
                        <th>Siguiente Formateado</th>
                        <th>Estado de Uso</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($secuencias as $secuencia)
                        @php
                            $enUso = ($secuenciaActivaId === (string) $secuencia->id);
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold text-white d-flex align-items-center gap-2">
                                    <i class="bi bi-tag text-secondary"></i>
                                    {{ $secuencia->name }}
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-white">{{ number_format($secuencia->next) }}</span>
                            </td>
                            <td>
                                <span class="text-secondary">+{{ $secuencia->increment }}</span>
                            </td>
                            <td>
                                <span class="text-secondary">{{ $secuencia->padding }} dígitos</span>
                            </td>
                            <td>
                                <code class="text-danger fw-bold bg-transparent p-0">{{ $secuencia->obtenerNumeroFormateado() }}</code>
                            </td>
                            <td>
                                @if($enUso)
                                    <span class="badge rounded-pill px-2 py-1 fw-bold" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                        <i class="bi bi-check-circle-fill me-1"></i> Secuencia Activa
                                    </span>
                                @else
                                    <span class="text-muted small">No activa</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-light border-0 rounded-circle btn-editar-secuencia" 
                                            title="Editar"
                                            data-id="{{ $secuencia->id }}"
                                            data-action="{{ route('admin.sequences.update', $secuencia->id) }}"
                                            data-name="{{ $secuencia->name }}"
                                            data-next="{{ $secuencia->next }}"
                                            data-increment="{{ $secuencia->increment }}"
                                            data-padding="{{ $secuencia->padding }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarSecuencia">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-eliminar-secuencia" 
                                            title="Eliminar secuencia"
                                            data-action="{{ route('admin.sequences.destroy', $secuencia->id) }}"
                                            data-name="{{ $secuencia->name }}"
                                            {{ $enUso ? 'disabled' : '' }}
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminarSecuencia">
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

    <!-- Modal Nueva Secuencia usando x-agent.modal -->
    <x-agent.modal 
        id="modalNuevaSecuencia" 
        titulo="Registrar Nueva Secuencia Numérica" 
        subtitulo="Crea un correlativo para la generación secuencial de tickets" 
        icono="bi-plus-circle" 
        colorIcono="text-danger" 
        accion="{{ route('admin.sequences.store') }}" 
        metodo="POST" 
        textoBoton="Guardar Secuencia" 
        colorBoton="btn-danger" 
        iconoBoton="bi-check-circle"
        tamano="modal-md"
    >
        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Nombre de la Secuencia *</label>
            <input type="text" name="name" class="form-control" placeholder="Ej: General, Secuencia 2026" required>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Siguiente Número *</label>
                <input type="number" name="next" class="form-control" value="1" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Incremento *</label>
                <input type="number" name="increment" class="form-control" value="1" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Relleno Ceros (0-20)</label>
                <input type="number" name="padding" class="form-control" value="6" min="0" max="20" required>
            </div>
        </div>
    </x-agent.modal>

    <!-- Modal Editar Secuencia usando x-agent.modal -->
    <x-agent.modal 
        id="modalEditarSecuencia" 
        titulo="Editar Secuencia Numérica" 
        subtitulo="Modifica los parámetros del contador correlativo" 
        icono="bi-pencil-square" 
        colorIcono="text-danger" 
        accion="#" 
        metodo="PUT" 
        textoBoton="Guardar Cambios" 
        colorBoton="btn-danger" 
        iconoBoton="bi-check-circle"
        tamano="modal-md"
    >
        <div class="mb-3">
            <label class="form-label small fw-bold text-secondary">Nombre de la Secuencia *</label>
            <input type="text" name="name" id="edit_seq_name" class="form-control" required>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Siguiente Número *</label>
                <input type="number" name="next" id="edit_seq_next" class="form-control" min="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Incremento *</label>
                <input type="number" name="increment" id="edit_seq_increment" class="form-control" min="1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-secondary">Relleno Ceros (0-20)</label>
                <input type="number" name="padding" id="edit_seq_padding" class="form-control" min="0" max="20" required>
            </div>
        </div>
    </x-agent.modal>

    <!-- Modal Eliminar Secuencia usando x-delete-modal -->
    <x-delete-modal
        id="modalEliminarSecuencia"
        title="Eliminar Secuencia Numérica"
        message="¿Estás seguro de que deseas eliminar esta secuencia numérica? Esta acción no se puede deshacer."
        submitText="Eliminar Secuencia"
        formAction="#"
    />

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Llenado dinámico del modal de edición de secuencia
            const botonesEditar = document.querySelectorAll('.btn-editar-secuencia');
            const formularioEditar = document.getElementById('modalEditarSecuencia-formulario');

            botonesEditar.forEach(btn => {
                btn.addEventListener('click', function () {
                    if (formularioEditar) {
                        formularioEditar.action = this.dataset.action;
                    }
                    document.getElementById('edit_seq_name').value = this.dataset.name || '';
                    document.getElementById('edit_seq_next').value = this.dataset.next || 1;
                    document.getElementById('edit_seq_increment').value = this.dataset.increment || 1;
                    document.getElementById('edit_seq_padding').value = this.dataset.padding || 6;
                });
            });

            // Configuración dinámica del modal de eliminación
            const botonesEliminar = document.querySelectorAll('.btn-eliminar-secuencia');
            const formularioEliminar = document.getElementById('modalEliminarSecuencia-form');

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
