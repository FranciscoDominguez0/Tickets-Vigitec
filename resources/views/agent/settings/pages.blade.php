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
        .settings-badge {
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .settings-badge.badge-default {
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }
        .settings-badge.badge-custom {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.35);
        }
        .settings-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 1.5rem;
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
        .settings-card .form-control {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.92rem;
            transition: all 0.2s;
        }
        .settings-card .form-control:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        .image-preview-box {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 18px;
            max-width: 520px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .settings-tabs {
            border-bottom: 2px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 1.5rem;
            gap: 0.5rem;
        }
        .settings-tabs .nav-link {
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.75rem 1.25rem;
            margin-bottom: -2px;
            background: transparent;
            transition: all 0.2s;
            font-size: 0.92rem;
        }
        .settings-tabs .nav-link:hover {
            color: #ef4444;
        }
        .settings-tabs .nav-link.active {
            color: #ef4444;
            border-bottom-color: #ef4444;
            background: transparent;
        }
        input[type="file"].form-control {
            background: #121417 !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: #e4e4e7 !important;
        }
        input[type="file"].form-control::file-selector-button {
            background: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            border-radius: 6px;
            padding: 0.35rem 0.75rem;
            margin-right: 0.75rem;
        }
    </style>
    @endpush

    <div class="container-fluid px-0">
        <!-- Hero Encabezado de Configuración -->
        <div class="settings-hero">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div class="d-flex align-items-center gap-3">
                    <span class="settings-hero-icon"><i class="bi bi-building"></i></span>
                    <div>
                        <h1 class="h3 text-white mb-1 fw-bold">Perfil de la empresa</h1>
                        <p class="text-muted mb-0 small">Administrar información institucional y branding de la plataforma</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="settings-badge {{ $modoLogo === 'custom' ? 'badge-custom' : 'badge-default' }}">
                        <i class="bi {{ $modoLogo === 'custom' ? 'bi-image-fill' : 'bi-image' }}"></i>
                        Logo: {{ $modoLogo === 'custom' ? 'Personalizado' : 'Default' }}
                    </span>
                    <span class="settings-badge {{ $modoFondo === 'custom' ? 'badge-custom' : 'badge-default' }}">
                        <i class="bi {{ $modoFondo === 'custom' ? 'bi-card-image' : 'bi-image' }}"></i>
                        Fondo login: {{ $modoFondo === 'custom' ? 'Personalizado' : 'Default' }}
                    </span>
                </div>
            </div>
        </div>

        @if($objetivo !== 'pages')
            <div class="alert alert-secondary text-white" style="background-color: #16191d; border-color: rgba(255,255,255,0.08); border-radius: 12px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill text-danger fs-5"></i>
                    <div>
                        <strong>Módulo en desarrollo:</strong> La pestaña <code>t={{ $objetivo }}</code> está vinculada en el sidebar de administración.
                    </div>
                </div>
            </div>
        @endif

        <!-- Navegación por pestañas -->
        <ul class="nav nav-tabs settings-tabs mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestañaActiva === 'basic' ? 'active' : '' }}" id="tab-basic-btn" data-bs-toggle="tab" data-bs-target="#tab-basic" type="button" role="tab" data-tab="basic">
                    <i class="bi bi-info-circle me-1"></i> Información básica
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestañaActiva === 'logos' ? 'active' : '' }}" id="tab-logos-btn" data-bs-toggle="tab" data-bs-target="#tab-logos" type="button" role="tab" data-tab="logos">
                    <i class="bi bi-image me-1"></i> Logos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $pestañaActiva === 'login' ? 'active' : '' }}" id="tab-login-btn" data-bs-toggle="tab" data-bs-target="#tab-login" type="button" role="tab" data-tab="login">
                    <i class="bi bi-card-image me-1"></i> Fondo del login
                </button>
            </li>
        </ul>

        <!-- Formulario de Configuración -->
        <form action="{{ route('agent.settings.pages.store') }}" method="POST" enctype="multipart/form-data" id="form-settings-pages">
            @csrf
            <input type="hidden" name="active_tab" id="active_tab" value="{{ $pestañaActiva }}">

            <div class="tab-content">
                <!-- Pestaña 1: Información básica -->
                <div class="tab-pane fade {{ $pestañaActiva === 'basic' ? 'show active' : '' }}" id="tab-basic" role="tabpanel">
                    <div class="card settings-card">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="bi bi-building text-danger"></i>
                            <span>Información de la empresa</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label">Nombre de la empresa <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" id="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $nombreEmpresa) }}" placeholder="Ej. Vigitec Panamá">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="company_website" class="form-label">Sitio Web</label>
                                    <input type="text" name="company_website" id="company_website" class="form-control @error('company_website') is-invalid @enderror" value="{{ old('company_website', $sitioWeb) }}" placeholder="https://www.vigitecpanama.com">
                                    @error('company_website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="company_phone" class="form-label">Teléfono</label>
                                    <input type="text" name="company_phone" id="company_phone" class="form-control @error('company_phone') is-invalid @enderror" value="{{ old('company_phone', $telefono) }}" placeholder="+507 6621-0000">
                                    @error('company_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="company_address" class="form-label">Dirección</label>
                                    <textarea name="company_address" id="company_address" class="form-control @error('company_address') is-invalid @enderror" rows="3" placeholder="Dirección física o sede corporativa">{{ old('company_address', $direccion) }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña 2: Logos -->
                <div class="tab-pane fade {{ $pestañaActiva === 'logos' ? 'show active' : '' }}" id="tab-logos" role="tabpanel">
                    <div class="card settings-card">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="bi bi-image text-danger"></i>
                            <span>Logotipo del Sistema</span>
                        </div>
                        <div class="card-body">
                            <!-- Opción 1: Logo por defecto -->
                            <div class="mb-4">
                                <div class="fw-semibold text-white mb-2">Logo de sistema por defecto</div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="company_logo_mode" id="logo-mode-default" value="default" {{ $modoLogo === 'default' ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="logo-mode-default">Predeterminado (Sistema)</label>
                                </div>
                                <div class="image-preview-box">
                                    <img src="{{ asset('img/vigitec-logo.webp') }}" alt="Logo Default" style="max-height: 70px; width: auto; max-width: 100%;">
                                </div>
                            </div>

                            <hr style="border-color: rgba(255,255,255,0.08);">

                            <!-- Opción 2: Logo personalizado -->
                            <div class="mb-3">
                                <div class="fw-semibold text-white mb-2">Utilizar un logotipo personalizado</div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="company_logo_mode" id="logo-mode-custom" value="custom" {{ $modoLogo === 'custom' ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="logo-mode-custom">Personalizado</label>
                                </div>

                                <div class="image-preview-box mb-3 {{ !$logoPersonalizado ? 'd-none' : '' }}" id="custom-logo-preview-container">
                                    <img src="{{ $logoPersonalizado ? asset('storage/' . $logoPersonalizado) : '' }}" id="custom-logo-preview-img" alt="Logo Personalizado" style="max-height: 70px; width: auto; max-width: 100%;">
                                </div>

                                @if(!$logoPersonalizado)
                                    <div class="alert alert-secondary text-muted small" style="max-width: 520px; background-color: #121417; border-color: rgba(255,255,255,0.08);" id="custom-logo-no-preview-alert">
                                        No hay un logotipo personalizado cargado aún.
                                    </div>
                                @endif

                                <div class="mt-3" style="max-width: 520px;">
                                    <label class="form-label">Subir nuevo logotipo</label>
                                    <input type="file" name="company_logo" class="form-control @error('company_logo') is-invalid @enderror" accept="image/*">
                                    <div class="form-text text-muted small">Formatos permitidos: PNG, JPG, JPEG, WEBP, GIF (Máx. 5MB).</div>
                                    @error('company_logo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pestaña 3: Fondo del login -->
                <div class="tab-pane fade {{ $pestañaActiva === 'login' ? 'show active' : '' }}" id="tab-login" role="tabpanel">
                    <div class="card settings-card">
                        <div class="card-header d-flex align-items-center gap-2">
                            <i class="bi bi-card-image text-danger"></i>
                            <span>Fondo de Pantalla de Inicio de Sesión</span>
                        </div>
                        <div class="card-body">
                            <!-- Opción 1: Fondo por defecto -->
                            <div class="mb-4">
                                <div class="fw-semibold text-white mb-2">Fondo por defecto del sistema</div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="login_bg_mode" id="bg-mode-default" value="default" {{ $modoFondo === 'default' ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="bg-mode-default">Staff (Predeterminado)</label>
                                </div>
                                <div class="image-preview-box">
                                    <img src="{{ asset('img/agent-background.webp') }}" alt="Fondo Default" style="height: 120px; width: auto; max-width: 100%; object-fit: cover; border-radius: 8px;">
                                </div>
                            </div>

                            <hr style="border-color: rgba(255,255,255,0.08);">

                            <!-- Opción 2: Fondo personalizado -->
                            <div class="mb-3">
                                <div class="fw-semibold text-white mb-2">Use un fondo personalizado</div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="login_bg_mode" id="bg-mode-custom" value="custom" {{ $modoFondo === 'custom' ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="bg-mode-custom">Personalizado</label>
                                </div>

                                <div class="image-preview-box mb-3 {{ !$fondoPersonalizado ? 'd-none' : '' }}" id="custom-bg-preview-container">
                                    <img src="{{ $fondoPersonalizado ? asset('storage/' . $fondoPersonalizado) : '' }}" id="custom-bg-preview-img" alt="Fondo Personalizado" style="height: 120px; width: auto; max-width: 100%; object-fit: cover; border-radius: 8px;">
                                </div>

                                @if(!$fondoPersonalizado)
                                    <div class="alert alert-secondary text-muted small" style="max-width: 520px; background-color: #121417; border-color: rgba(255,255,255,0.08);" id="custom-bg-no-preview-alert">
                                        No hay un fondo personalizado cargado aún.
                                    </div>
                                @endif

                                <div class="mt-3" style="max-width: 520px;">
                                    <label class="form-label">Subir archivo de fondo</label>
                                    <input type="file" name="login_background" class="form-control @error('login_background') is-invalid @enderror" accept="image/*">
                                    <div class="form-text text-muted small">Formatos permitidos: PNG, JPG, JPEG, WEBP, GIF (Máx. 8MB).</div>
                                    @error('login_background')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="d-flex align-items-center gap-2 mt-4">
                <button type="submit" class="btn btn-danger fw-bold px-4 py-2" style="background-color: #ef4444; border-color: #ef4444; border-radius: 8px;">
                    <i class="bi bi-check-lg me-1"></i> Guardar cambios
                </button>
                <a href="{{ route('agent.settings.pages', ['t' => 'pages']) }}" class="btn btn-outline-secondary px-3 py-2 fw-semibold" style="border-radius: 8px;">
                    Restaurar
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Previsualización de imágenes en tiempo real
        function configurarPrevisualizacion(inputName, idImg, idContainer, idAlert, idRadio) {
            const input = document.querySelector('input[name="' + inputName + '"]');
            if (!input) return;

            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const lector = new FileReader();
                    lector.onload = function(e) {
                        const img = document.getElementById(idImg);
                        const contenedor = document.getElementById(idContainer);
                        const alerta = document.getElementById(idAlert);
                        const radio = document.getElementById(idRadio);

                        if (img) img.src = e.target.result;
                        if (contenedor) contenedor.classList.remove('d-none');
                        if (alerta) alerta.classList.add('d-none');
                        if (radio) radio.checked = true;
                    };
                    lector.readAsDataURL(this.files[0]);
                }
            });
        }

        configurarPrevisualizacion('company_logo', 'custom-logo-preview-img', 'custom-logo-preview-container', 'custom-logo-no-preview-alert', 'logo-mode-custom');
        configurarPrevisualizacion('login_background', 'custom-bg-preview-img', 'custom-bg-preview-container', 'custom-bg-no-preview-alert', 'bg-mode-custom');

        // Control y sincronización de pestañas activas
        const activeTabInput = document.getElementById('active_tab');
        const tabLinks = document.querySelectorAll('#settingsTabs button[data-bs-toggle="tab"]');

        tabLinks.forEach(function(link) {
            link.addEventListener('shown.bs.tab', function() {
                const nombreTab = this.getAttribute('data-tab');
                if (activeTabInput) {
                    activeTabInput.value = nombreTab;
                }
                const url = new URL(window.location);
                url.searchParams.set('tab', nombreTab);
                window.history.replaceState({}, '', url);
            });
        });
    });
    </script>
    @endpush
</x-agent.admin-layout>

