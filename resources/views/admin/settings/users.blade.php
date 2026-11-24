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
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Configuración de Usuarios y Clientes</h1>
                    <p class="text-secondary small mb-0">Gestiona las políticas de registro, autenticación de clientes, acceso al portal y colaboración entre organizaciones.</p>
                </div>
            </div>
            <a href="{{ route('admin.staff') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                <i class="bi bi-person-badge me-2"></i> Ver Personal y Agentes
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
    <form action="{{ route('admin.settings.users.store') }}" method="POST">
        @csrf
        <input type="hidden" name="active_tab" id="active_tab_input" value="{{ $pestanaActiva }}">

        <!-- Pestañas de Navegación -->
        <ul class="nav settings-tabs" id="userSettingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestanaActiva === 'general' ? 'active' : '' }}" id="tab-general-btn" data-bs-toggle="tab" data-bs-target="#tab-general" type="button" role="tab" onclick="document.getElementById('active_tab_input').value='general'">
                    <i class="bi bi-person-check me-2"></i> Registro y Acceso
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestanaActiva === 'organizations' ? 'active' : '' }}" id="tab-organizations-btn" data-bs-toggle="tab" data-bs-target="#tab-organizations" type="button" role="tab" onclick="document.getElementById('active_tab_input').value='organizations'">
                    <i class="bi bi-building me-2"></i> Organizaciones y Colaboración
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestanaActiva === 'notices' ? 'active' : '' }}" id="tab-notices-btn" data-bs-toggle="tab" data-bs-target="#tab-notices" type="button" role="tab" onclick="document.getElementById('active_tab_input').value='notices'">
                    <i class="bi bi-megaphone me-2"></i> Avisos del Portal
                </button>
            </li>
        </ul>

        <div class="tab-content" id="userSettingsTabsContent">
            <!-- ========================================== -->
            <!-- PESTAÑA 1: REGISTRO Y ACCESO              -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $pestanaActiva === 'general' ? 'show active' : '' }}" id="tab-general" role="tabpanel">
                <!-- Tarjeta 1: Modo de Registro -->
                <div class="settings-card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-shield-lock me-2 text-danger"></i> Modo de Registro de Usuarios</span>
                        <span class="text-secondary small fw-normal">Controla quién puede crear una cuenta</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <!-- Opción Público -->
                            <div class="col-md-4">
                                <label class="option-radio-card d-block {{ old('client_registration', $modoRegistro) === 'public' ? 'selected' : '' }}" for="reg_mode_public">
                                    <div class="form-check p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 me-2" type="radio" name="client_registration" id="reg_mode_public" value="public" {{ old('client_registration', $modoRegistro) === 'public' ? 'checked' : '' }}>
                                                <span class="fw-bold text-white">Público</span>
                                            </div>
                                            <span class="badge bg-success bg-opacity-25 text-success badge-mode">Recomendado</span>
                                        </div>
                                        <p class="text-secondary small mb-0">Cualquier persona puede registrarse en el portal y crear una cuenta de cliente de forma autónoma.</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Opción Privado -->
                            <div class="col-md-4">
                                <label class="option-radio-card d-block {{ old('client_registration', $modoRegistro) === 'private' ? 'selected' : '' }}" for="reg_mode_private">
                                    <div class="form-check p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 me-2" type="radio" name="client_registration" id="reg_mode_private" value="private" {{ old('client_registration', $modoRegistro) === 'private' ? 'checked' : '' }}>
                                                <span class="fw-bold text-white">Privado</span>
                                            </div>
                                            <span class="badge bg-info bg-opacity-25 text-info badge-mode">Solo Agentes</span>
                                        </div>
                                        <p class="text-secondary small mb-0">Solo los agentes y administradores pueden dar de alta cuentas. El registro público está deshabilitado.</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Opción Desactivado -->
                            <div class="col-md-4">
                                <label class="option-radio-card d-block {{ old('client_registration', $modoRegistro) === 'disabled' ? 'selected' : '' }}" for="reg_mode_disabled">
                                    <div class="form-check p-0">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input class="form-check-input ms-0 me-2" type="radio" name="client_registration" id="reg_mode_disabled" value="disabled" {{ old('client_registration', $modoRegistro) === 'disabled' ? 'checked' : '' }}>
                                                <span class="fw-bold text-white">Desactivado</span>
                                            </div>
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary badge-mode">Bloqueado</span>
                                        </div>
                                        <p class="text-secondary small mb-0">No se permite crear cuentas de clientes ni por el portal ni automáticamente.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Opciones de Registro y Validación -->
                        <div class="p-3 rounded-3" style="background-color: #121417; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="client_registration_required" name="client_registration_required" value="1" {{ old('client_registration_required', $registroRequerido) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="client_registration_required">
                                    Requerir registro e inicio de sesión para crear tickets
                                </label>
                                <div class="text-secondary small">Si está marcado, solo los usuarios registrados e identificados pueden abrir tickets. Si está desactivado, se admiten solicitudes de visitantes (tickets de invitados).</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="client_verify_email" name="client_verify_email" value="1" {{ old('client_verify_email', $verificarEmail) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="client_verify_email">
                                    Requerir verificación de correo electrónico
                                </label>
                                <div class="text-secondary small">Exige que los clientes confirmen su cuenta mediante un enlace enviado a su correo antes de acceder al portal completo.</div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="client_allow_pw_reset" name="client_allow_pw_reset" value="1" {{ old('client_allow_pw_reset', $permitirRestablecerClave) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="client_allow_pw_reset">
                                    Permitir restablecimiento de contraseña en línea
                                </label>
                                <div class="text-secondary small">Habilita el enlace de recuperación de contraseña ("¿Olvidaste tu contraseña?") en la pantalla de inicio de sesión.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Sesiones y Límites -->
                <div class="settings-card">
                    <div class="card-header">
                        <i class="bi bi-clock-history me-2 text-danger"></i> Sesiones y Límites de Tickets
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="client_session_timeout" class="form-label">Tiempo de Expiración de Sesión (Minutos)</label>
                                <div class="input-group">
                                    <input type="number" min="0" max="1440" step="5" class="form-control @error('client_session_timeout') is-invalid @enderror" id="client_session_timeout" name="client_session_timeout" value="{{ old('client_session_timeout', $tiempoExpiracionSesion) }}" required>
                                    <span class="input-group-text bg-dark border-secondary text-secondary">min</span>
                                </div>
                                <div class="form-text text-secondary small">Minutos de inactividad antes de cerrar la sesión automáticamente. Use 0 para sesión indefinida mientras el navegador permanezca abierto.</div>
                                @error('client_session_timeout')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="client_max_open_tickets" class="form-label">Máximo de Tickets Abiertos por Cliente</label>
                                <input type="number" min="0" max="999" class="form-control @error('client_max_open_tickets') is-invalid @enderror" id="client_max_open_tickets" name="client_max_open_tickets" value="{{ old('client_max_open_tickets', $maxTicketsAbiertos) }}" required>
                                <div class="form-text text-secondary small">Cantidad máxima de tickets simultáneos que un cliente puede tener sin resolver. Ingrese 0 para ilimitados.</div>
                                @error('client_max_open_tickets')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PESTAÑA 2: ORGANIZACIONES Y COLABORACIÓN   -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $pestanaActiva === 'organizations' ? 'show active' : '' }}" id="tab-organizations" role="tabpanel">
                <!-- Tarjeta 1: Políticas de Organizaciones -->
                <div class="settings-card">
                    <div class="card-header">
                        <i class="bi bi-buildings me-2 text-danger"></i> Reglas de Organizaciones y Empresas Cliente
                    </div>
                    <div class="card-body">
                        <div class="p-3 rounded-3 mb-4" style="background-color: #121417; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="auto_assign_organization" name="auto_assign_organization" value="1" {{ old('auto_assign_organization', $asignarOrganizacionAuto) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="auto_assign_organization">
                                    Asignación automática a organizaciones por dominio de email
                                </label>
                                <div class="text-secondary small">Si el correo electrónico del cliente coincide con el dominio registrado de una organización (ej. @acme.com), el usuario quedará vinculado automáticamente.</div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="org_members_see_all_tickets" name="org_members_see_all_tickets" value="1" {{ old('org_members_see_all_tickets', $miembrosVenTicketsOrg) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="org_members_see_all_tickets">
                                    Permitir a contactos principales ver todos los tickets de su organización
                                </label>
                                <div class="text-secondary small">Otorga visibilidad a los administradores de la organización para consultar y hacer seguimiento de los tickets enviados por otros colaboradores de su misma empresa.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Colaboradores -->
                <div class="settings-card">
                    <div class="card-header">
                        <i class="bi bi-people me-2 text-danger"></i> Colaboradores en Tickets
                    </div>
                    <div class="card-body">
                        <div class="p-3 rounded-3 mb-4" style="background-color: #121417; border: 1px solid rgba(255,255,255,0.06);">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="auto_add_collaborators" name="auto_add_collaborators" value="1" {{ old('auto_add_collaborators', $autoAgregarColaboradores) ? 'checked' : '' }}>
                                <label class="form-check-label text-white fw-semibold" for="auto_add_collaborators">
                                    Agregar automáticamente colaboradores en copia (CC)
                                </label>
                                <div class="text-secondary small">Cuando un cliente responda o envíe un ticket con direcciones en copia (CC), se añadirán automáticamente como participantes del ticket.</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="max_collaborators_per_ticket" class="form-label">Máximo de Colaboradores por Ticket</label>
                                <input type="number" min="0" max="50" class="form-control @error('max_collaborators_per_ticket') is-invalid @enderror" id="max_collaborators_per_ticket" name="max_collaborators_per_ticket" value="{{ old('max_collaborators_per_ticket', $maxColaboradores) }}" required>
                                <div class="form-text text-secondary small">Número límite de colaboradores externos que pueden participar en el hilo de un mismo ticket (entre 0 y 50).</div>
                                @error('max_collaborators_per_ticket')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PESTAÑA 3: AVISOS Y MENSAJES DEL PORTAL    -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $pestanaActiva === 'notices' ? 'show active' : '' }}" id="tab-notices" role="tabpanel">
                <div class="settings-card">
                    <div class="card-header">
                        <i class="bi bi-chat-square-quote me-2 text-danger"></i> Mensajes y Avisos Personalizados
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="client_welcome_msg" class="form-label">Mensaje de Bienvenida en el Portal de Clientes</label>
                            <textarea class="form-control @error('client_welcome_msg') is-invalid @enderror" id="client_welcome_msg" name="client_welcome_msg" rows="4" placeholder="Ej: ¡Bienvenido a nuestro centro de soporte! Puedes abrir una nueva solicitud o consultar el estado de tus tickets existentes...">{{ old('client_welcome_msg', $mensajeBienvenida) }}</textarea>
                            <div class="form-text text-secondary small">Mensaje informativo o de bienvenida que se exhibe en el encabezado del portal público de clientes.</div>
                            @error('client_welcome_msg')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="client_guest_ticket_notice" class="form-label">Aviso para Creación de Tickets por Invitados</label>
                            <textarea class="form-control @error('client_guest_ticket_notice') is-invalid @enderror" id="client_guest_ticket_notice" name="client_guest_ticket_notice" rows="4" placeholder="Ej: Por favor ingresa una dirección de correo válida para que podamos responder a tu solicitud y enviarte el enlace de seguimiento...">{{ old('client_guest_ticket_notice', $avisoInvitados) }}</textarea>
                            <div class="form-text text-secondary small">Instrucciones o advertencia que se muestra a los usuarios cuando envían un ticket sin iniciar sesión.</div>
                            @error('client_guest_ticket_notice')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
            // Actualizar estilo visual al cambiar el radio de modo de registro
            const radioCards = document.querySelectorAll('.option-radio-card');
            const radios = document.querySelectorAll('input[name="client_registration"]');

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
