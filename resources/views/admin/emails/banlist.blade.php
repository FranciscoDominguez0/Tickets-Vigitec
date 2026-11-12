<x-agent.admin-layout>
    @push('styles')
    <style>
        .banlist-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .banlist-hero-icon {
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

        .banlist-control-bar {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 0.85rem 1.25rem;
            margin-bottom: 1.25rem;
        }

        .banlist-table-wrapper {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .banlist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .banlist-table th {
            background-color: #1a1e24;
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .banlist-table td {
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            vertical-align: middle;
            font-size: 0.88rem;
        }

        .banlist-table tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        .badge-activo {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .badge-inactivo {
            background: rgba(148, 163, 184, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(148, 163, 184, 0.25);
            font-size: 0.72rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
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
    <div class="banlist-hero">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="banlist-hero-icon">
                    <i class="bi bi-slash-circle"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Lista de Prohibidos (Banlist)</h1>
                    <p class="text-secondary small mb-0">Bloquea direcciones de correo electrónico o dominios completos para impedir la creación de tickets no deseados o spam.</p>
                </div>
            </div>
            <button type="button" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoBloqueo" style="background-color: #ef4444; border-color: #ef4444;">
                <i class="bi bi-plus-lg me-1"></i> Agregar Bloqueo
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
    <div class="banlist-control-bar d-flex align-items-center justify-content-between">
        <div class="text-secondary small fw-semibold">
            Total de reglas activas/registradas: <strong class="text-white">{{ $prohibidos->count() }}</strong>
        </div>
    </div>

    <!-- Tabla de Prohibidos -->
    <div class="banlist-table-wrapper">
        @if($prohibidos->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-shield-check fs-1 mb-2 d-block opacity-50"></i>
                <h5 class="fw-bold text-white">No hay direcciones o dominios prohibidos</h5>
                <p class="small text-secondary mb-3">La lista de prohibición está vacía. Cualquier remitente legítimo puede generar solicitudes.</p>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoBloqueo">
                    Bloquear un correo o dominio
                </button>
            </div>
        @else
            <table class="banlist-table">
                <thead>
                    <tr>
                        <th>Dirección / Dominio Prohibido</th>
                        <th>Tipo</th>
                        <th>Notas / Motivo</th>
                        <th>Estado</th>
                        <th>Fecha de Registro</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($prohibidos as $prohibido)
                        <tr>
                            <td>
                                <div class="fw-bold text-white d-flex align-items-center gap-2">
                                    <i class="bi {{ $prohibido->email ? 'bi-envelope-slash' : 'bi-globe' }} text-danger"></i>
                                    {{ $prohibido->obtenerBloqueo() }}
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-2 py-1 small" style="background: rgba(255, 255, 255, 0.08); color: #cbd5e1;">
                                    {{ $prohibido->email ? 'Correo Específico' : 'Dominio Completo' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $prohibido->notes ?: 'Sin notas' }}</span>
                            </td>
                            <td>
                                @if($prohibido->is_active)
                                    <span class="badge-activo">Activo</span>
                                @else
                                    <span class="badge-inactivo">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-secondary small">{{ $prohibido->created ? $prohibido->created->format('d/m/Y H:i') : '-' }}</span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <form action="{{ route('admin.banlist.toggle', $prohibido->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill py-0 px-2" style="font-size: 0.72rem;" title="Alternar estado">
                                            {{ $prohibido->is_active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.banlist.destroy', $prohibido->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este registro de la lista de prohibidos?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle" title="Eliminar regla">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal Nuevo Bloqueo -->
    <div class="modal fade modal-dark" id="modalNuevoBloqueo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.banlist.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-white">Prohibir Correo o Dominio</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Tipo de Regla *</label>
                            <select name="type" id="tipoBloqueo" class="form-select" required>
                                <option value="email" selected>Dirección de Correo Específica</option>
                                <option value="domain">Dominio Completo (@dominio.com)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary" id="labelValorBloqueo">Dirección de Correo *</label>
                            <input type="text" name="value" id="inputValorBloqueo" class="form-control" placeholder="ejemplo@spam.com" required>
                            <div class="form-text text-secondary small" id="ayudaValorBloqueo">Ingresa el correo completo que deseas bloquear.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Notas o Motivo del Bloqueo</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Razón del bloqueo o referencia..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-semibold" style="background-color: #ef4444; border-color: #ef4444;">Bloquear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectTipo = document.getElementById('tipoBloqueo');
            const labelValor = document.getElementById('labelValorBloqueo');
            const inputValor = document.getElementById('inputValorBloqueo');
            const ayudaValor = document.getElementById('ayudaValorBloqueo');

            if (selectTipo && labelValor && inputValor) {
                selectTipo.addEventListener('change', function () {
                    if (this.value === 'domain') {
                        labelValor.textContent = 'Nombre de Dominio *';
                        inputValor.placeholder = 'spam.com o @spam.com';
                        ayudaValor.textContent = 'Todos los correos pertenecientes a este dominio serán bloqueados automáticamente.';
                    } else {
                        labelValor.textContent = 'Dirección de Correo *';
                        inputValor.placeholder = 'ejemplo@spam.com';
                        ayudaValor.textContent = 'Ingresa el correo electrónico específico que deseas bloquear.';
                    }
                });
            }
        });
    </script>
    @endpush
</x-agent.admin-layout>

