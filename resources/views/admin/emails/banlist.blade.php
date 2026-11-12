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
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="banlist-hero">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="banlist-hero-icon">
                    <i class="bi bi-slash-circle"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Lista de Prohibidos (Banlist)</h1>
                    <p class="text-secondary small mb-0">Bloquea correos o dominios no deseados para prevenir spam y solicitudes no autorizadas en el sistema.</p>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-danger rounded-pill px-3 py-2 fw-semibold shadow-sm small" style="background-color: #ef4444; border-color: #ef4444;" data-bs-toggle="modal" data-bs-target="#modalNuevoBloqueo">
                    <i class="bi bi-plus-lg me-1"></i> Prohibir Correo / Dominio
                </button>
            </div>
        </div>
    </div>

    <!-- Barra de Control -->
    <div class="banlist-control-bar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <span class="badge rounded-pill bg-dark border border-secondary px-3 py-2 text-secondary">
                <i class="bi bi-shield-x me-1"></i> Reglas de Bloqueo
            </span>
        </div>
        <div class="text-secondary small fw-semibold">
            Total de registros: <strong class="text-white">{{ $prohibidos->count() }}</strong>
        </div>
    </div>

    <!-- Tabla Banlist -->
    <div class="banlist-table-wrapper">
        @if($prohibidos->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-shield-check fs-1 mb-2 d-block opacity-50 text-success"></i>
                <h5 class="fw-bold text-white">No hay elementos prohibidos</h5>
                <p class="small text-secondary mb-3">Tu sistema no tiene direcciones de correo o dominios bloqueados actualmente.</p>
                <button type="button" class="btn btn-outline-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalNuevoBloqueo">
                    Agregar regla de prohibición
                </button>
            </div>
        @else
            <table class="banlist-table">
                <thead>
                    <tr>
                        <th>Elemento Bloqueado</th>
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

                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger border-0 rounded-circle btn-eliminar-bloqueo" 
                                            title="Eliminar regla"
                                            data-action="{{ route('admin.banlist.destroy', $prohibido->id) }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEliminarBloqueo">
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

    <!-- Modal Nuevo Bloqueo usando x-agent.modal -->
    <x-agent.modal 
        id="modalNuevoBloqueo" 
        titulo="Prohibir Correo o Dominio" 
        subtitulo="Evita que remitentes específicos o dominios completos generen tickets" 
        icono="bi-slash-circle" 
        colorIcono="text-danger" 
        accion="{{ route('admin.banlist.store') }}" 
        metodo="POST" 
        textoBoton="Registrar Prohibición" 
        colorBoton="btn-danger" 
        iconoBoton="bi-shield-x"
        tamano="modal-md"
    >
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

        <div class="mb-2">
            <label class="form-label small fw-bold text-secondary">Notas / Motivo del Bloqueo</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Ej: Remitente de publicidad masiva o actividad maliciosa recurrente."></textarea>
        </div>
    </x-agent.modal>

    <!-- Modal Eliminar Bloqueo usando x-delete-modal -->
    <x-delete-modal
        id="modalEliminarBloqueo"
        title="Eliminar Regla de Prohibición"
        message="¿Estás seguro de que deseas eliminar este registro de la lista de prohibidos? El correo o dominio volverá a ser admitido por el sistema."
        submitText="Eliminar Regla"
        formAction="#"
    />

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

            // Configuración dinámica del modal de eliminación
            const botonesEliminar = document.querySelectorAll('.btn-eliminar-bloqueo');
            const formularioEliminar = document.getElementById('modalEliminarBloqueo-form');

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
