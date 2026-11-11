<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="{{ asset('css/scp/scp.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scp/dark.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scp/tickets.css') }}">
    
    <style>
        /* Estilos específicos para la barra lateral y navegación del Administrador */
        body.scp-panel {
            background-color: var(--body-bg, #090d16);
            color: #e2e8f0;
            overflow-x: hidden;
        }

        /* Barra de navegación superior (separada del sidebar) */
        .scp-admin-navbar {
            position: fixed;
            top: 0;
            left: 280px;
            width: calc(100% - 280px);
            height: 60px;
            z-index: 1020;
            background-color: #000000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0 1.5rem;
            transition: left 0.25s ease, width 0.25s ease;
        }

        /* Barra lateral (Sidebar) de altura completa e independiente */
        .sidebar.admin-sidebar-panel {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            background: radial-gradient(ellipse 260px 140px at 50% 0%, rgba(220, 38, 38, 0.28) 0%, rgba(0, 0, 0, 0) 100%), #000000;
            padding: 0 0 36px 0;
            overflow-y: auto;
            z-index: 1030;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Encabezado del sidebar */
        .sidebar-brand-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .sidebar-brand-btn:hover {
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.35);
        }

        .sidebar-brand-text {
            color: #ffffff;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }

        .sidebar-section-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            margin: 12px 0 10px 10px;
            font-weight: 700;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-link,
        .sidebar .sidebar-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            color: #9ca3af;
            text-decoration: none;
            font-size: 0.88rem;
            margin-bottom: 3px;
            min-height: 40px;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            transition: background 0.15s ease, color 0.15s ease, transform 0.1s ease;
            white-space: nowrap;
            overflow: hidden;
            cursor: pointer;
        }

        .sidebar-link:hover,
        .sidebar .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #f1f5f9;
        }

        /* Contenedor de iconos tipo squircle */
        .sidebar-icon {
            display: inline-flex;
            width: 28px;
            height: 28px;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            background: rgba(148, 163, 184, 0.1);
            border: 1px solid rgba(148, 163, 184, 0.16);
            flex: 0 0 28px;
            font-size: 14px;
            color: inherit;
            transition: all .15s ease;
        }

        .sidebar-link:hover .sidebar-icon,
        .sidebar .sidebar-toggle:hover .sidebar-icon {
            border-color: rgba(255, 255, 255, 0.28);
            color: #ffffff;
        }

        .sidebar-toggle .arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
            color: inherit;
        }

        .sidebar-toggle.expanded .arrow {
            transform: rotate(90deg);
        }

        /* Submenús con línea vertical guía */
        .sidebar-subnav {
            list-style: none;
            margin: 4px 0 6px 14px;
            padding-left: 12px;
            border-left: 1px solid rgba(148, 163, 184, 0.22);
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
            transition: max-height 0.25s ease-out, opacity 0.2s ease-out;
        }

        .sidebar-subnav.open {
            max-height: 800px;
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar-subnav .sidebar-link {
            padding: 6px 10px;
            font-size: 0.85rem;
            color: #9ca3af;
            margin-bottom: 2px;
            min-height: 36px;
            border-radius: 8px;
        }

        .sidebar-subnav .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            transform: translateX(2px);
        }

        .sidebar-subnav .sidebar-link.active {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(153, 27, 27, 0.2));
            color: #ffffff;
            border-left: 2px solid #ef4444;
            padding-left: 8px;
        }

        .sidebar-subnav .sidebar-link.active .sidebar-icon {
            background: rgba(239, 68, 68, 0.35);
            border-color: rgba(239, 68, 68, 0.6);
            color: #ffffff;
        }

        /* Botón destacado Panel de Control (Rojo vibrante según diseño) */
        .panel-control-toggle {
            background: linear-gradient(135deg, #ef4444, #991b1b) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
            border-radius: 8px !important;
        }

        .panel-control-toggle:hover {
            background: linear-gradient(135deg, #f87171, #b91c1c) !important;
            color: #ffffff !important;
            transform: translateX(1px);
        }

        .panel-control-toggle .icon-panel-control {
            background: rgba(255, 255, 255, 0.18) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
            color: #ffffff !important;
        }

        /* Contenedor principal de contenidos */
        .main-shell {
            margin-left: 280px;
            margin-top: 60px;
            min-height: calc(100vh - 60px);
            padding: 24px 28px 40px;
            background-color: var(--body-bg, #090d16);
            transition: margin-left 0.25s ease;
        }

        @media (max-width: 991px) {
            .scp-admin-navbar {
                left: 0;
                width: 100%;
            }
            .sidebar.admin-sidebar-panel {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 0 14px 32px rgba(0, 0, 0, 0.6);
            }
            .sidebar.admin-sidebar-panel.show {
                transform: translateX(0);
            }
            .main-shell {
                margin-left: 0;
                padding: 16px 12px 32px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="scp-panel {{ (auth('staff')->check() && !auth('staff')->user()->dark_mode) ? '' : 'dark-mode' }}">
    <!-- NAVBAR SUPERIOR ADMINISTRADOR (Área de contenido) -->
    <nav class="navbar navbar-dark scp-admin-navbar">
        <div class="container-fluid d-flex align-items-center justify-content-between px-0">
            <div class="d-flex align-items-center gap-2">
                <!-- Botón móvil para mostrar/ocultar el menú lateral -->
                <button class="btn scp-menu-toggle text-white p-1 d-lg-none" id="scpSidebarToggle" type="button" aria-label="Alternar menú lateral" style="background: none; border: none; font-size: 1.35rem;">
                    <i class="bi bi-list"></i>
                </button>
            </div>
            
            <div class="d-flex align-items-center gap-3 ms-auto">
                <a href="{{ route('agent.dashboard') }}" class="scp-admin-pill scp-admin-pill-lg d-none d-md-inline-flex align-items-center gap-1" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; text-decoration: none; font-weight: 600; font-size: 0.85rem; padding: 6px 14px; border-radius: 999px; transition: all 0.2s;">
                    <i class="bi bi-arrow-left"></i> Volver a Agentes
                </a>

                @php
                    $nombreStaff = auth('staff')->user()->firstname ?? 'Admin';
                    $apellidoStaff = auth('staff')->user()->lastname ?? '';
                    $iniciales = strtoupper(substr($nombreStaff, 0, 1) . substr($apellidoStaff, 0, 1));
                    if (trim($iniciales) === '') {
                        $iniciales = 'AD';
                    }
                @endphp
                <div class="dropdown">
                    <button class="btn dropdown-toggle d-flex align-items-center gap-2 p-1 text-white border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background: none;">
                        <span class="d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px; border-radius: 50%; background: #ef4444; font-size: 0.8rem;">
                            {{ $iniciales }}
                        </span>
                        <span class="d-none d-sm-inline fw-semibold text-white" style="font-size: 0.88rem;">{{ $nombreStaff }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="background-color: #16191d; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <li class="d-md-none">
                            <a class="dropdown-item text-danger fw-semibold" href="{{ route('agent.dashboard') }}">
                                <i class="bi bi-arrow-left me-2"></i> Volver a Agentes
                            </a>
                        </li>
                        <li class="d-md-none"><hr class="dropdown-divider border-secondary"></li>
                        <li>
                            <a class="dropdown-item text-white" href="{{ route('agent.profile.index') }}">
                                <i class="bi bi-person me-2"></i> Mi perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                        <li>
                            <form method="POST" action="{{ route('agent.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Salir
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="layout">
        <!-- BARRA LATERAL ADMINISTRADOR COMO COMPONENTE INDEPENDIENTE -->
        <x-agent.admin-sidebar />

        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-shell">
            <div class="container-fluid px-0">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Bootstrap Bundle con Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mensajes Toast -->
    <x-agent.toast />

    <!-- Script de interacción de la barra lateral -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Despliegue de submenús
            const toggles = document.querySelectorAll('.sidebar-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-subnav');
                    const targetMenu = document.getElementById(targetId);
                    
                    this.classList.toggle('expanded');
                    const isExpanded = this.classList.contains('expanded');
                    this.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                    
                    if (targetMenu) {
                        targetMenu.classList.toggle('open', isExpanded);
                    }
                });
            });

            // Toggle lateral en dispositivos móviles y botón de cabecera
            const btnMenuMovil = document.getElementById('scpSidebarToggle');
            const btnSidebarHeader = document.getElementById('adminSidebarToggleBtn');
            const sidebar = document.querySelector('.sidebar.admin-sidebar-panel');
            
            if (btnMenuMovil && sidebar) {
                btnMenuMovil.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            if (btnSidebarHeader && sidebar) {
                btnSidebarHeader.addEventListener('click', function() {
                    if (window.innerWidth <= 991) {
                        sidebar.classList.toggle('show');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
