<x-agent.admin-layout>
    @push('styles')
    <style>
        .permissions-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .permissions-hero-icon {
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

        .perm-group-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .perm-group-header {
            background-color: #1a1e24;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .perm-item-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .perm-item-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
            background-color: rgba(255, 255, 255, 0.02);
            transform: translateY(-1px);
        }

        .form-switch .form-check-input {
            width: 2.4em;
            height: 1.25em;
            cursor: pointer;
        }

        .action-bar-sticky {
            position: sticky;
            bottom: 20px;
            z-index: 900;
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 14px 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="permissions-hero">
        <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <span class="permissions-hero-icon"><i class="bi bi-shield-check"></i></span>
                <div>
                    <h2 class="text-white fw-bold mb-1 fs-4">Permisos del Rol</h2>
                    <p class="text-secondary mb-0" style="font-size: 0.9rem;">
                        Configurando matriz de privilegios para: <span class="badge bg-primary px-3 py-1 fs-6 ms-1">{{ $rol->name }}</span>
                    </p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.roles') }}" class="btn btn-outline-secondary px-3 py-2 rounded-3 d-inline-flex align-items-center gap-2 fw-semibold" style="border-radius: 8px;">
                    <i class="bi bi-arrow-left"></i> Volver a Roles
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario de Permisos -->
    <form method="POST" action="{{ route('admin.roles.permissions.store', ['nombre' => $rol->name]) }}" id="formPermisos">
        @csrf
        <input type="hidden" name="do" value="save" id="hiddenActionDo">

        <div class="row g-4">
            @php
                $iconosGrupos = [
                    'Tickets' => ['icono' => 'bi-ticket-detailed-fill', 'color' => '#3b82f6'],
                    'Tareas' => ['icono' => 'bi-check2-square', 'color' => '#10b981'],
                    'Directorio, Mapa y Estadísticas' => ['icono' => 'bi-compass-fill', 'color' => '#8b5cf6'],
                    'Administración' => ['icono' => 'bi-sliders', 'color' => '#ef4444'],
                    'Cotizaciones' => ['icono' => 'bi-file-earmark-text-fill', 'color' => '#f97316'],
                    'Requisiciones e Inventario' => ['icono' => 'bi-box-seam-fill', 'color' => '#14b8a6'],
                ];
            @endphp

            <!-- Columna Izquierda: Tickets -->
            <div class="col-12 col-xl-6">
                @if(isset($gruposPermisos['Tickets']))
                    @php
                        $metaGrupo = $iconosGrupos['Tickets'] ?? ['icono' => 'bi-shield', 'color' => '#3b82f6'];
                        $grupoId = 'grupo_tickets';
                    @endphp
                    <div class="perm-group-card">
                        <div class="perm-group-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-5" style="color: {{ $metaGrupo['color'] }};"><i class="bi {{ $metaGrupo['icono'] }}"></i></span>
                                <h3 class="h6 mb-0 fw-bold text-white fs-6">Tickets</h3>
                            </div>
                            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                                <label class="form-check-label text-secondary small fw-semibold" for="{{ $grupoId }}_toggle" style="cursor: pointer;">Marcar todos</label>
                                <input class="form-check-input switch-grupo" type="checkbox" id="{{ $grupoId }}_toggle" data-clase-grupo="{{ $grupoId }}">
                            </div>
                        </div>
                        <div class="p-3 d-flex flex-column gap-2">
                            @foreach($gruposPermisos['Tickets'] as $clave => $item)
                                @php $activo = isset($permisosAsignados[$clave]); @endphp
                                <div class="perm-item-card d-flex align-items-start justify-content-between gap-3" onclick="alternarSwitch(this)">
                                    <div>
                                        <div class="fw-bold text-white mb-1" style="font-size: 0.9rem;">{{ $item['titulo'] }}</div>
                                        <p class="text-secondary mb-0 small" style="line-height: 1.4;">{{ $item['descripcion'] }}</p>
                                    </div>
                                    <div class="form-check form-switch pt-1" onclick="event.stopPropagation();">
                                        <input class="form-check-input perm-switch {{ $grupoId }}" type="checkbox" name="perms[]" value="{{ $clave }}" {{ $activo ? 'checked' : '' }}>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Columna Derecha: Resto de Grupos -->
            <div class="col-12 col-xl-6">
                @foreach($gruposPermisos as $nombreGrupo => $permisos)
                    @if($nombreGrupo === 'Tickets') @continue @endif
                    @php
                        $metaGrupo = $iconosGrupos[$nombreGrupo] ?? ['icono' => 'bi-shield', 'color' => '#3b82f6'];
                        $grupoId = 'grupo_' . preg_replace('/[^a-z0-9]/', '', strtolower($nombreGrupo));
                    @endphp
                    <div class="perm-group-card">
                        <div class="perm-group-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fs-5" style="color: {{ $metaGrupo['color'] }};"><i class="bi {{ $metaGrupo['icono'] }}"></i></span>
                                <h3 class="h6 mb-0 fw-bold text-white fs-6">{{ $nombreGrupo }}</h3>
                            </div>
                            <div class="form-check form-switch mb-0 d-flex align-items-center gap-2">
                                <label class="form-check-label text-secondary small fw-semibold" for="{{ $grupoId }}_toggle" style="cursor: pointer;">Marcar todos</label>
                                <input class="form-check-input switch-grupo" type="checkbox" id="{{ $grupoId }}_toggle" data-clase-grupo="{{ $grupoId }}">
                            </div>
                        </div>
                        <div class="p-3 d-flex flex-column gap-2">
                            @foreach($permisos as $clave => $item)
                                @php $activo = isset($permisosAsignados[$clave]); @endphp
                                <div class="perm-item-card d-flex align-items-start justify-content-between gap-3" onclick="alternarSwitch(this)">
                                    <div>
                                        <div class="fw-bold text-white mb-1" style="font-size: 0.9rem;">{{ $item['titulo'] }}</div>
                                        <p class="text-secondary mb-0 small" style="line-height: 1.4;">{{ $item['descripcion'] }}</p>
                                    </div>
                                    <div class="form-check form-switch pt-1" onclick="event.stopPropagation();">
                                        <input class="form-check-input perm-switch {{ $grupoId }}" type="checkbox" name="perms[]" value="{{ $clave }}" {{ $activo ? 'checked' : '' }}>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Barra Fija Inferior de Guardado -->
        <div class="action-bar-sticky d-flex align-items-center justify-content-between gap-3 flex-wrap mt-4">
            <div>
                <button type="button" class="btn btn-outline-secondary px-3 py-2 fw-semibold text-secondary" id="btnRestablecerPermisos">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer Permisos
                </button>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.roles') }}" class="btn btn-secondary px-4 py-2 fw-semibold" style="border-radius: 8px;">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: 0; border-radius: 8px;">
                    <i class="bi bi-check-circle-fill"></i> Guardar Permisos
                </button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        function alternarSwitch(card) {
            const switchEl = card.querySelector('.perm-switch');
            if (switchEl) {
                switchEl.checked = !switchEl.checked;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Switches de "Marcar todos" por grupo
            const switchesGrupo = document.querySelectorAll('.switch-grupo');
            switchesGrupo.forEach(sw => {
                const clase = sw.getAttribute('data-clase-grupo');
                const checks = document.querySelectorAll('.' + clase);

                // Comprobar estado inicial
                const todosMarcados = checks.length > 0 && Array.from(checks).every(c => c.checked);
                sw.checked = todosMarcados;

                sw.addEventListener('change', function() {
                    checks.forEach(c => c.checked = sw.checked);
                });

                checks.forEach(c => {
                    c.addEventListener('change', function() {
                        sw.checked = Array.from(checks).every(ch => ch.checked);
                    });
                });
            });

            // Botón restablecer
            const btnRestablecer = document.getElementById('btnRestablecerPermisos');
            const form = document.getElementById('formPermisos');
            const hiddenAction = document.getElementById('hiddenActionDo');

            if (btnRestablecer) {
                btnRestablecer.addEventListener('click', function() {
                    if (confirm('¿Estás seguro de que deseas restablecer todos los permisos de este rol?')) {
                        hiddenAction.value = 'reset';
                        form.submit();
                    }
                });
            }
        });
    </script>
    @endpush
</x-agent.admin-layout>

