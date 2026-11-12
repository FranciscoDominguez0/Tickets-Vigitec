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

        .modal-dark .form-control {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .modal-dark .form-control:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="seq-hero">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="seq-hero-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Gestión de Secuencias Numéricas</h1>
                    <p class="text-secondary small mb-0">Define secuencias incrementales y formato de relleno de ceros para la numeración consecutiva de tickets.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.settings.tickets') }}" class="btn btn-outline-light rounded-pill px-3 py-2 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Solicitudes
                </a>
                <button type="button" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaSecuencia" style="background-color: #ef4444; border-color: #ef4444;">
                    <i class="bi bi-plus-lg me-1"></i> Nueva Secuencia
                </button>
            </div>
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
    <div class="seq-control-bar d-flex align-items-center justify-content-between">
        <div class="text-secondary small fw-semibold">
            Total de secuencias registradas: <strong class="text-white">{{ $secuencias->count() }}</strong>
        </div>
    </div>

    <!-- Tabla de Secuencias -->
    <div class="seq-table-wrapper">
        @if($secuencias->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-123 fs-1 mb-2 d-block opacity-50"></i>
                <h5 class="fw-bold text-white">No hay secuencias numéricas creadas</h5>
                <p class="small text-secondary mb-3">Puedes crear una secuencia para enumerar los tickets consecutivamente (ej: 000001, 000002...).</p>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNuevaSecuencia">
                    Crear primera secuencia
                </button>
            </div>
        @else
            <table class="seq-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Siguiente Número</th>
                        <th>Incremento</th>
                        <th>Relleno (Padding)</th>
                        <th>Ejemplo Generado</th>
                        <th>Uso en Solicitudes</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($secuencias as $secuencia)
                        @php
                            $enUso = ($secuenciaActivaId === (string)$secuencia->id);
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold text-white d-flex align-items-center gap-2">
                                    <i class="bi bi-hash text-danger"></i>
                                    {{ $secuencia->name }}
                                </div>
                            </td>
                            <td>
                                <strong class="text-white">{{ $secuencia->next }}</strong>
                            </td>
                            <td>+{{ $secuencia->increment }}</td>
                            <td>{{ $secuencia->padding > 0 ? $secuencia->padding . ' dígitos' : 'Sin relleno' }}</td>
                            <td>
                                <code class="px-2 py-1 rounded bg-black text-danger fw-bold border border-secondary" style="font-size: 0.85rem;">
                                    {{ $secuencia->obtenerNumeroFormateado() }}
                                </code>
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
                                            class="btn btn-sm btn-outline-light border-0 rounded-circle" 
                                            title="Editar"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarSecuencia{{ $secuencia->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('admin.sequences.destroy', $secuencia->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta secuencia numérica?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger border-0 rounded-circle" 
                                                title="Eliminar" 
                                                {{ $enUso ? 'disabled' : '' }}>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Editar Secuencia -->
                        <div class="modal fade modal-dark" id="modalEditarSecuencia{{ $secuencia->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.sequences.update', $secuencia->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold text-white">Editar Secuencia: {{ $secuencia->name }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-secondary">Nombre de la Secuencia *</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $secuencia->name) }}" required>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-secondary">Siguiente Número *</label>
                                                    <input type="number" name="next" class="form-control" value="{{ old('next', $secuencia->next) }}" min="0" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-secondary">Incremento *</label>
                                                    <input type="number" name="increment" class="form-control" value="{{ old('increment', $secuencia->increment) }}" min="1" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-secondary">Relleno Ceros (0-20)</label>
                                                    <input type="number" name="padding" class="form-control" value="{{ old('padding', $secuencia->padding) }}" min="0" max="20" required>
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

    <!-- Modal Nueva Secuencia -->
    <div class="modal fade modal-dark" id="modalNuevaSecuencia" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.sequences.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-white">Nueva Secuencia Numérica</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Nombre de la Secuencia *</label>
                            <input type="text" name="name" class="form-control" placeholder="Ej: Tickets Soporte 2026" required>
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
                        <div class="form-text text-secondary small mt-2">
                            Un valor de relleno de 6 dígitos convertirá el número 1 en <code>000001</code>.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background-color: #ef4444; border-color: #ef4444;">Crear Secuencia</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-agent.admin-layout>

