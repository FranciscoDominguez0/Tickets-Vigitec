<x-agent.layout>
    @push('styles')
    <style>
        .profile-page {
            max-width: 950px;
            margin: 0 auto;
            padding-bottom: 2rem;
        }
        .profile-avatar-section {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .profile-avatar {
            flex-shrink: 0;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
        }
        .profile-avatar-inner {
            font-size: 1.6rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
        }
        .profile-avatar-info {
            flex: 1;
        }
        .profile-avatar-name {
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
        }
        .profile-avatar-role {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 600;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .profile-stats-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .profile-stat-widget {
            flex: 1;
            min-width: 180px;
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s, border-color 0.2s;
        }
        .profile-stat-widget:hover {
            border-color: rgba(239, 68, 68, 0.35);
            transform: translateY(-2px);
        }
        .profile-stat-widget i {
            font-size: 1.4rem;
        }
        .widget-total i {
            color: #ef4444;
        }
        .widget-pending i {
            color: #f59e0b;
        }
        .widget-login i {
            color: #60a5fa;
        }
        .profile-stat-details {
            display: flex;
            flex-direction: column;
        }
        .profile-stat-num {
            font-size: 1.2rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.2;
        }
        .profile-stat-lbl {
            font-size: 0.72rem;
            color: rgba(255, 255, 255, 0.55);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.15rem;
        }
        .profile-tabs {
            border-bottom: 2px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 1.5rem;
            gap: 0.5rem;
        }
        .profile-tabs .nav-link {
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.75rem 1.25rem;
            margin-bottom: -2px;
            background: transparent;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        .profile-tabs .nav-link:hover {
            color: #ef4444;
        }
        .profile-tabs .nav-link.active {
            color: #ef4444;
            border-bottom-color: #ef4444;
            background: transparent;
        }
        .profile-card {
            background-color: #16191d;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .profile-section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .profile-section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 16px;
            background: #ef4444;
            border-radius: 2px;
        }
        .profile-card .form-label {
            font-weight: 700;
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.35rem;
        }
        .profile-card .form-control {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.92rem;
            transition: all 0.2s;
        }
        .profile-card .form-control:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        .profile-card .form-control-readonly {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.6) !important;
            cursor: not-allowed;
        }
        .profile-info-widget {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.9rem 1.1rem;
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
        }
        .profile-info-widget-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }
        .profile-info-widget-content {
            flex: 1;
        }
        .profile-info-widget-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin: 0;
        }
        .profile-info-widget-sub {
            font-size: 0.92rem;
            font-weight: 600;
            color: #ffffff;
            margin-top: 0.15rem;
        }
        .profile-hr {
            border-color: rgba(255, 255, 255, 0.08);
            opacity: 1;
        }
        .profile-theme-selector-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            margin-top: 0.75rem;
        }
        .profile-theme-option {
            position: relative;
            cursor: pointer;
        }
        .profile-theme-option input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .profile-theme-card {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background-color: #121417;
            transition: all 0.2s;
        }
        .profile-theme-card:hover {
            border-color: rgba(255, 255, 255, 0.25);
        }
        .profile-theme-option input:checked + .profile-theme-card {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.08);
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.25);
        }
        .profile-theme-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .theme-light-icon {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }
        .theme-dark-icon {
            background: rgba(99, 102, 241, 0.15);
            color: #818cf8;
        }
        .profile-theme-card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }
        .profile-theme-card-sub {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.5);
            margin: 0;
        }
    </style>
    @endpush

    <div class="profile-page">
        <!-- Componente de encabezado -->
        <x-agent.page-header title="Mi Perfil" icon="<i class='bi bi-person-gear'></i>">
            Configuración de cuenta, seguridad y preferencias del agente
        </x-agent.page-header>

        @php
            $iniciales = strtoupper(substr($agente->firstname, 0, 1) . substr($agente->lastname, 0, 1));
        @endphp

        <!-- Sección de Avatar e Información Básica -->
        <div class="profile-avatar-section">
            <div class="profile-avatar" aria-hidden="true">
                <span class="profile-avatar-inner">{{ $iniciales }}</span>
            </div>
            <div class="profile-avatar-info">
                <h2 class="profile-avatar-name">{{ $agente->firstname }} {{ $agente->lastname }}</h2>
                <div class="profile-avatar-role">
                    <i class="bi bi-person-badge text-danger"></i>
                    <span>{{ '@' . $agente->username }} &bull; Agente de Soporte</span>
                </div>
            </div>
        </div>

        <!-- Fila de Estadísticas del Agente -->
        <div class="profile-stats-row">
            <div class="profile-stat-widget widget-total">
                <i class="bi bi-ticket-detailed-fill"></i>
                <div class="profile-stat-details">
                    <span class="profile-stat-num">{{ number_format($totalTickets) }}</span>
                    <span class="profile-stat-lbl">Total Solicitudes</span>
                </div>
            </div>
            <div class="profile-stat-widget widget-pending">
                <i class="bi bi-clock-history"></i>
                <div class="profile-stat-details">
                    <span class="profile-stat-num">{{ number_format($ticketsAbiertos) }}</span>
                    <span class="profile-stat-lbl">Pendientes</span>
                </div>
            </div>
            <div class="profile-stat-widget widget-login">
                <i class="bi bi-box-arrow-in-right"></i>
                <div class="profile-stat-details">
                    <span class="profile-stat-num" style="font-size: 0.95rem;">
                        {{ $agente->last_login ? $agente->last_login->format('d/m/Y H:i') : 'Activo' }}
                    </span>
                    <span class="profile-stat-lbl">Último Acceso</span>
                </div>
            </div>
        </div>

        <!-- Formulario Principal de Perfil -->
        <form action="{{ route('agent.profile.update') }}" method="POST" autocomplete="off" id="profile-form">
            @csrf
            @method('PUT')

            <ul class="nav nav-tabs profile-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab" aria-controls="account" aria-selected="true">
                        Cuenta
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab" aria-controls="preferences" aria-selected="false">
                        Preferencias
                    </button>
                </li>
            </ul>

            <div class="tab-content profile-tab-content" id="profileTabContent">
                <!-- Pestaña Cuenta -->
                <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                    <div class="profile-card">
                        <h3 class="profile-section-title">Información de la Cuenta</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname" name="firstname" value="{{ old('firstname', $agente->firstname) }}" maxlength="100" required>
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Apellido <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname" name="lastname" value="{{ old('lastname', $agente->lastname) }}" maxlength="100" required>
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label for="email" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $agente->email) }}" maxlength="255" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="profile-hr my-4">

                        <!-- Rol y Departamento -->
                        <h3 class="profile-section-title">Rol y Departamento</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="profile-info-widget">
                                    <div class="profile-info-widget-icon">
                                        <i class="bi bi-shield-lock"></i>
                                    </div>
                                    <div class="profile-info-widget-content">
                                        <div class="profile-info-widget-title">Rol Asignado</div>
                                        <div class="profile-info-widget-sub">{{ ucfirst($agente->role ?: 'Agente') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="profile-info-widget">
                                    <div class="profile-info-widget-icon">
                                        <i class="bi bi-diagram-3"></i>
                                    </div>
                                    <div class="profile-info-widget-content">
                                        <div class="profile-info-widget-title">Departamento</div>
                                        <div class="profile-info-widget-sub">{{ $agente->department->name ?? 'Soporte General' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="profile-hr my-4">

                        <!-- Autenticación -->
                        <h3 class="profile-section-title">Autenticación</h3>
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label">Nombre de usuario</label>
                                <input type="text" class="form-control form-control-readonly" value="{{ $agente->username }}" readonly disabled>
                            </div>
                            <div class="col-md-6 text-md-end pt-md-3">
                                <button type="button" class="btn btn-outline-danger px-3 py-2 fw-semibold" id="btn-change-password" data-bs-toggle="modal" data-bs-target="#modalChangePassword">
                                    <i class="bi bi-key-fill me-1"></i> Cambiar contraseña
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña Preferencias -->
                <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                    <div class="profile-card">
                        <h3 class="profile-section-title">Región y Zona Horaria</h3>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Zona horaria del sistema</label>
                                <input type="text" class="form-control form-control-readonly" value="{{ config('app.timezone', 'America/Panama') }}" readonly disabled>
                            </div>
                        </div>

                        <hr class="profile-hr my-4">

                        <!-- Apariencia -->
                        <h3 class="profile-section-title">Apariencia</h3>
                        <p class="text-muted small">Selecciona el tema visual para tu sesión de trabajo.</p>

                        <div class="profile-theme-selector-grid">
                            <label class="profile-theme-option">
                                <input type="radio" name="dark_mode" value="0" {{ old('dark_mode', (int)$agente->dark_mode) === 0 ? 'checked' : '' }}>
                                <div class="profile-theme-card">
                                    <div class="profile-theme-card-icon theme-light-icon">
                                        <i class="bi bi-sun-fill"></i>
                                    </div>
                                    <div class="profile-theme-card-text">
                                        <h4 class="profile-theme-card-title">Tema Claro</h4>
                                        <p class="profile-theme-card-sub">Clásico y luminoso.</p>
                                    </div>
                                </div>
                            </label>
                            <label class="profile-theme-option">
                                <input type="radio" name="dark_mode" value="1" {{ old('dark_mode', (int)$agente->dark_mode) === 1 ? 'checked' : '' }}>
                                <div class="profile-theme-card">
                                    <div class="profile-theme-card-icon theme-dark-icon">
                                        <i class="bi bi-moon-stars-fill"></i>
                                    </div>
                                    <div class="profile-theme-card-text">
                                        <h4 class="profile-theme-card-title">Tema Oscuro</h4>
                                        <p class="profile-theme-card-sub">Deep Black & Red corporativo.</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2 mt-4">
                <button type="submit" class="btn btn-danger px-4 py-2 fw-bold" style="background-color: #ef4444; border-color: #ef4444; border-radius: 8px;">
                    <i class="bi bi-check-lg me-1"></i> Guardar cambios
                </button>
                <button type="reset" class="btn btn-outline-secondary px-3 py-2 fw-semibold" style="border-radius: 8px;">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Restablecer
                </button>
                <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold" style="border-radius: 8px;">Cancelar</a>
            </div>
        </form>
    </div>

    <!-- Modal Cambiar Contraseña -->
    <div class="modal fade" id="modalChangePassword" tabindex="-1" aria-labelledby="modalChangePasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white" style="background-color: #16191d; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 14px;">
                <form action="{{ route('agent.profile.password') }}" method="POST" id="form-change-password">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
                        <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="modalChangePasswordLabel">
                            <i class="bi bi-shield-lock-fill text-danger"></i> Cambiar contraseña
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label for="current_password" class="form-label text-muted small fw-bold text-uppercase">Contraseña actual <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @if($errors->password->has('current_password')) is-invalid @endif" id="current_password" name="current_password" required autocomplete="current-password" placeholder="Ingresa tu contraseña actual" style="background-color: #121417; border-color: rgba(255,255,255,0.12); color: #fff; border-radius: 8px;">
                            @if($errors->password->has('current_password'))
                                <div class="invalid-feedback">{{ $errors->password->first('current_password') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label text-muted small fw-bold text-uppercase">Nueva contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @if($errors->password->has('password')) is-invalid @endif" id="new_password" name="password" required minlength="6" autocomplete="new-password" placeholder="Mínimo 6 caracteres" style="background-color: #121417; border-color: rgba(255,255,255,0.12); color: #fff; border-radius: 8px;">
                            @if($errors->password->has('password'))
                                <div class="invalid-feedback">{{ $errors->password->first('password') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-muted small fw-bold text-uppercase">Confirmar nueva contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="6" autocomplete="new-password" placeholder="Repite la nueva contraseña" style="background-color: #121417; border-color: rgba(255,255,255,0.12); color: #fff; border-radius: 8px;">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.08);">
                        <button type="button" class="btn btn-outline-secondary px-3 py-2" data-bs-dismiss="modal" style="border-radius: 8px;">Cancelar</button>
                        <button type="submit" class="btn btn-danger fw-bold px-3 py-2" style="background-color: #ef4444; border-color: #ef4444; border-radius: 8px;">Actualizar contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->password->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('modalChangePassword');
            if (modalEl && typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(modalEl).show();
            }
        });
    </script>
    @endif
</x-agent.layout>
