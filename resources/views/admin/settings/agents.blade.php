<x-agent.admin-layout>
    @push('styles')
    <style>
        .settings-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .settings-hero-icon {
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

        .settings-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .settings-card .card-header {
            background-color: #121417;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.1rem 1.5rem;
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .settings-card .card-body {
            padding: 1.5rem;
        }

        .settings-card .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        .settings-card .form-control,
        .settings-card .form-select {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.92rem;
            transition: all 0.2s;
        }

        .settings-card .form-control:focus,
        .settings-card .form-select:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .settings-tabs {
            border-bottom: 2px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 1.5rem;
            gap: 0.5rem;
        }

        .settings-tabs .nav-link {
            color: rgba(255, 255, 255, 0.6);
            border: none;
            border-bottom: 2px solid transparent;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.92rem;
            transition: all 0.2s;
            background: none;
            margin-bottom: -2px;
        }

        .settings-tabs .nav-link:hover {
            color: #ffffff;
            border-bottom-color: rgba(239, 68, 68, 0.4);
        }

        .settings-tabs .nav-link.active {
            color: #ef4444;
            border-bottom-color: #ef4444;
            background: none;
        }

        .option-radio-card {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            transition: all 0.2s;
            cursor: pointer;
            height: 100%;
        }

        .option-radio-card:hover {
            border-color: rgba(239, 68, 68, 0.4);
            background-color: rgba(239, 68, 68, 0.04);
        }

        .option-radio-card.selected {
            border-color: #ef4444;
            background-color: rgba(239, 68, 68, 0.08);
        }

        .form-check-input {
            background-color: #121417;
            border-color: rgba(255, 255, 255, 0.25);
        }

        .form-check-input:checked {
            background-color: #ef4444;
            border-color: #ef4444;
        }

        .form-check-input:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 0.25rem rgba(239, 68, 68, 0.25);
        }

        .badge-mode {
            padding: 0.35rem 0.65rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="settings-hero">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="settings-hero-icon">
                    <i class="bi bi-person-gear"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Configuración de Agentes y Personal</h1>
                    <p class="text-secondary small mb-0">Define las directrices globales para los agentes de soporte: formato de nombres, avatares, caducidad de claves y seguridad de sesiones.</p>
                </div>
            </div>
            <a href="{{ route('admin.staff') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                <i class="bi bi-person-badge me-2"></i> Directorio de Personal
            </a>
        </div>
    </div>

    <!-- Alertas Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 mb-4 shadow" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0 mb-4 shadow" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show bg-danger text-white border-0 mb-4 shadow" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i> Errores de validación:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <!-- Formulario Principal -->
    <form action="{{ route('admin.settings.agents.store') }}" method="POST">
        @csrf
        <input type="hidden" name="active_tab" id="active_tab_input" value="{{ $pestanaActiva }}">

        <!-- Pestañas de Navegación -->
        <ul class="nav settings-tabs" id="agentSettingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestanaActiva === 'general' ? 'active' : '' }}" id="tab-general-btn" data-bs-toggle="tab" data-bs-target="#tab-general" type="button" role="tab" onclick="document.getElementById('active_tab_input').value='general'">
                    <i class="bi bi-person-badge me-2"></i> General y Visualización
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestanaActiva === 'passwords' ? 'active' : '' }}" id="tab-passwords-btn" data-bs-toggle="tab" data-bs-target="#tab-passwords" type="button" role="tab" onclick="document.getElementById('active_tab_input').value='passwords'">
                    <i class="bi bi-key me-2"></i> Políticas de Contraseñas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestanaActiva === 'security' ? 'active' : '' }}" id="tab-security-btn" data-bs-toggle="tab" data-bs-target="#tab-security" type="button" role="tab" onclick="document.getElementById('active_tab_input').value='security'">
                    <i class="bi bi-shield-check me-2"></i> Seguridad y Sesiones
                </button>
            </li>
        </ul>

        <div class="tab-content" id="agentSettingsTabsContent">
            <!-- ========================================== -->
            <!-- PESTAÑA 1: GENERAL Y VISUALIZACIÓN         -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $pestanaActiva === 'general' ? 'show active' : '' }}" id="tab-general" role="tabpanel">
                <!-- Tarjeta 1: Formato de Nombre -->
                <div class="settings-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-type me-2 text-danger"></i> Formato de Nombre de Agentes</span>
                        <span class="text-secondary small fw-normal">Cómo se muestra el nombre del agente en la plataforma</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <!-- Nombre y Apellido -->
                            <div class="col-md-4">
                                <label class="option-radio-card d-block {{ old('staff_name_format', $formatoNombre) === 'first_last' ? 'selected' : '' }}" for="name_first_last">
                                    <div class="form-check p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 me-2" type="radio" name="staff_name_format" id="name_first_last" value="first_last" {{ old('staff_name_format', $formatoNombre) === 'first_last' ? 'checked' : '' }}>
                                                <span class="fw-bold text-white">Nombre y Apellido</span>
                                            </div>
                                            <span class="badge bg-success bg-opacity-25 text-success badge-mode">Estándar</span>
                                        </div>
                                        <p class="text-secondary small mb-0">Ejemplo: <code>Carlos Pérez</code>. Recomendado para entornos corporativos cordiales.</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Apellido, Nombre -->
                            <div class="col-md-4">
                                <label class="option-radio-card d-block {{ old('staff_name_format', $formatoNombre) === 'last_first' ? 'selected' : '' }}" for="name_last_first">
                                    <div class="form-check p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 me-2" type="radio" name="staff_name_format" id="name_last_first" value="last_first" {{ old('staff_name_format', $formatoNombre) === 'last_first' ? 'checked' : '' }}>
                                                <span class="fw-bold text-white">Apellido, Nombre</span>
                                            </div>
                                            <span class="badge bg-info bg-opacity-25 text-info badge-mode">Formal</span>
                                        </div>
                                        <p class="text-secondary small mb-0">Ejemplo: <code>Pérez, Carlos</code>. Ideal para listados estructurados por orden alfabético.</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Nombre de Usuario -->
                            <div class="col-md-4">
                                <label class="option-radio-card d-block {{ old('staff_name_format', $formatoNombre) === 'username' ? 'selected' : '' }}" for="name_username">
                                    <div class="form-check p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 me-2" type="radio" name="staff_name_format" id="name_username" value="username" {{ old('staff_name_format', $formatoNombre) === 'username' ? 'checked' : '' }}>
                                                <span class="fw-bold text-white">Usuario (Username)</span>
                                            </div>
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary badge-mode">Alias</span>
                                        </div>
                                        <p class="text-secondary small mb-0">Ejemplo: <code>cperez</code>. Prioriza el identificador de inicio de sesión.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Opciones de Identidad y Avatares -->
                        <div class="p-3 rounded-3" style="background-color: #121417; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="hide_staff_name" name="hide_staff_name" value="1" {{ old('hide_staff_name', $ocultarIdentidad) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="hide_staff_name">
                                    Ocultar nombre real del agente ante el cliente
                                </label>
                                <div class="text-secondary small">Cuando esté habilitado, en las respuestas enviadas al cliente se enmascarará el nombre del agente y solo figurará el nombre del departamento o de la empresa.</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="disable_agent_collaborators" name="disable_agent_collaborators" value="1" {{ old('disable_agent_collaborators', $deshabilitarColaboradores) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="disable_agent_collaborators">
                                    Deshabilitar agentes como colaboradores automáticos
                                </label>
                                <div class="text-secondary small">Impide que otros agentes sean agregados como colaboradores en tickets ajenos para reducir el volumen de alertas por correo.</div>
                            </div>

                            <div class="row pt-2">
                                <div class="col-md-6">
                                    <label for="agent_avatar_source" class="form-label">Origen de Avatares y Fotos de Perfil</label>
                                    <select class="form-select @error('agent_avatar_source') is-invalid @enderror" id="agent_avatar_source" name="agent_avatar_source" required>
                                        <option value="initials" {{ old('agent_avatar_source', $origenAvatar) === 'initials' ? 'selected' : '' }}>Iniciales Generadas (Recomendado)</option>
                                        <option value="gravatar" {{ old('agent_avatar_source', $origenAvatar) === 'gravatar' ? 'selected' : '' }}>Gravatar (Basado en el correo electrónico)</option>
                                        <option value="disabled" {{ old('agent_avatar_source', $origenAvatar) === 'disabled' ? 'selected' : '' }}>Desactivado (Icono genérico)</option>
                                    </select>
                                    <div class="form-text text-secondary small">Define el proveedor predeterminado para renderizar las imágenes de perfil del personal.</div>
                                    @error('agent_avatar_source')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PESTAÑA 2: POLÍTICAS DE CONTRASEÑAS        -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $pestanaActiva === 'passwords' ? 'show active' : '' }}" id="tab-passwords" role="tabpanel">
                <div class="settings-card">
                    <div class="card-header">
                        <i class="bi bi-shield-lock me-2 text-danger"></i> Reglas y Expiración de Contraseñas
                    </div>
                    <div class="card-body">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="staff_password_expiration_days" class="form-label">Días de Expiración de Contraseña</label>
                                <div class="input-group">
                                    <input type="number" min="0" max="365" class="form-control @error('staff_password_expiration_days') is-invalid @enderror" id="staff_password_expiration_days" name="staff_password_expiration_days" value="{{ old('staff_password_expiration_days', $diasExpiracionClave) }}" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">días</span>
                                </div>
                                <div class="form-text text-secondary small">Número de días tras los cuales se obliga al agente a renovar su clave. Ingrese 0 para que nunca expire.</div>
                                @error('staff_password_expiration_days')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="staff_password_min_length" class="form-label">Longitud Mínima de Contraseña</label>
                                <div class="input-group">
                                    <input type="number" min="6" max="32" class="form-control @error('staff_password_min_length') is-invalid @enderror" id="staff_password_min_length" name="staff_password_min_length" value="{{ old('staff_password_min_length', $longitudMinimaClave) }}" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">caracteres</span>
                                </div>
                                <div class="form-text text-secondary small">Cantidad mínima de caracteres requerida al crear o actualizar contraseñas de personal (entre 6 y 32).</div>
                                @error('staff_password_min_length')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="p-3 rounded-3" style="background-color: #121417; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="staff_allow_pw_reset" name="staff_allow_pw_reset" value="1" {{ old('staff_allow_pw_reset', $permitirRestablecerClave) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="staff_allow_pw_reset">
                                    Permitir restablecimiento de contraseña en línea
                                </label>
                                <div class="text-secondary small">Muestra el enlace "¿Olvidaste tu contraseña?" en el formulario de inicio de sesión de agentes.</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="staff_password_require_symbols" name="staff_password_require_symbols" value="1" {{ old('staff_password_require_symbols', $requerirSimbolosClave) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="staff_password_require_symbols">
                                    Requerir complejidad alta (números y símbolos)
                                </label>
                                <div class="text-secondary small">Obliga a incluir al menos una mayúscula, un dígito y un carácter especial al definir una nueva clave.</div>
                            </div>

                            <div class="row pt-2">
                                <div class="col-md-6">
                                    <label for="staff_pw_reset_window" class="form-label">Tiempo de Vigencia del Enlace de Restablecimiento</label>
                                    <div class="input-group">
                                        <input type="number" min="5" max="1440" step="5" class="form-control @error('staff_pw_reset_window') is-invalid @enderror" id="staff_pw_reset_window" name="staff_pw_reset_window" value="{{ old('staff_pw_reset_window', $vigenciaTokenClave) }}" required>
                                        <span class="input-group-text bg-dark border-secondary text-secondary">min</span>
                                    </div>
                                    <div class="form-text text-secondary small">Minutos de validez del token enviado por correo antes de expirar (entre 5 y 1440).</div>
                                    @error('staff_pw_reset_window')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PESTAÑA 3: SEGURIDAD Y SESIONES            -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $pestanaActiva === 'security' ? 'show active' : '' }}" id="tab-security" role="tabpanel">
                <div class="settings-card">
                    <div class="card-header">
                        <i class="bi bi-shield-exclamation me-2 text-danger"></i> Protección Contra Accesos No Autorizados y Sesiones
                    </div>
                    <div class="card-body">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="staff_session_timeout" class="form-label">Tiempo de Expiración de Sesión de Agente</label>
                                <div class="input-group">
                                    <input type="number" min="0" max="1440" step="5" class="form-control @error('staff_session_timeout') is-invalid @enderror" id="staff_session_timeout" name="staff_session_timeout" value="{{ old('staff_session_timeout', $tiempoExpiracionSesion) }}" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">min</span>
                                </div>
                                <div class="form-text text-secondary small">Minutos de inactividad antes de cerrar la sesión automáticamente. Use 0 para sesión persistente.</div>
                                @error('staff_session_timeout')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="staff_max_login_attempts" class="form-label">Intentos Fallidos Máximos Permitidos</label>
                                <input type="number" min="1" max="20" class="form-control @error('staff_max_login_attempts') is-invalid @enderror" id="staff_max_login_attempts" name="staff_max_login_attempts" value="{{ old('staff_max_login_attempts', $intentosMaximosLogin) }}" required>
                                <div class="form-text text-secondary small">Cantidad de contraseñas erróneas antes de aplicar un bloqueo temporal de acceso (entre 1 y 20).</div>
                                @error('staff_max_login_attempts')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="staff_lockout_duration" class="form-label">Duración del Bloqueo Temporal</label>
                                <div class="input-group">
                                    <input type="number" min="1" max="1440" step="5" class="form-control @error('staff_lockout_duration') is-invalid @enderror" id="staff_lockout_duration" name="staff_lockout_duration" value="{{ old('staff_lockout_duration', $duracionBloqueo) }}" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">min</span>
                                </div>
                                <div class="form-text text-secondary small">Minutos que permanecerá suspendido el inicio de sesión tras exceder los intentos fallidos.</div>
                                @error('staff_lockout_duration')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="p-3 rounded-3" style="background-color: #121417; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="staff_bind_agent_ip" name="staff_bind_agent_ip" value="1" {{ old('staff_bind_agent_ip', $vincularIpSesion) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="staff_bind_agent_ip">
                                    Vincular sesión a la dirección IP de origen
                                </label>
                                <div class="text-secondary small">Cierra la sesión automáticamente si la dirección IP del agente cambia durante la navegación (máxima protección contra secuestro de sesión).</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="d-flex align-items-center justify-content-end gap-3 mt-4 pt-3 border-top border-secondary border-opacity-25">
            <a href="{{ route('admin.settings.pages') }}" class="btn btn-outline-secondary px-4 py-2">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4 py-2 fw-semibold">
                <i class="bi bi-floppy-fill"></i>
                <span>Guardar Cambios</span>
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Actualizar estilo visual al cambiar el radio de formato de nombre
            const radioCards = document.querySelectorAll('.option-radio-card');
            const radios = document.querySelectorAll('input[name="staff_name_format"]');

            radios.forEach(radio => {
                radio.addEventListener('change', function () {
                    radioCards.forEach(card => card.classList.remove('selected'));
                    const parentCard = this.closest('.option-radio-card');
                    if (parentCard) {
                        parentCard.classList.add('selected');
                    }
                });
            });
        });
    </script>
    @endpush
</x-agent.admin-layout>
