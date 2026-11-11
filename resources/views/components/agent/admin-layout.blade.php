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

        .scp-admin-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            z-index: 1001;
            background-color: #000000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0 1.25rem;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            width: 280px;
            min-width: 280px;
            max-width: 280px;
            background-color: #000000;
            padding: 16px 12px 36px;
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-section-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            margin: 10px 0 12px 6px;
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
            padding: 9px 12px;
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

        .sidebar-link span.icon,
        .sidebar .sidebar-toggle span.icon {
            display: inline-flex;
            width: 30px;
            height: 30px;
            justify-content: center;
            align-items: center;
            border-radius: 9px;
            background: rgba(148, 163, 184, 0.12);
            border: 1px solid rgba(148, 163, 184, 0.18);
            flex: 0 0 30px;
            font-size: 16px;
            color: inherit;
            transition: transform .15s ease, background .2s ease, border-color .2s ease;
        }

        .sidebar-toggle .arrow {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
        }

        .sidebar-toggle.expanded .arrow {
            transform: rotate(90deg);
        }

        /* Submenús con línea vertical guía */
        .sidebar-subnav {
            list-style: none;
            margin: 4px 0 6px 16px;
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
            padding: 7px 10px;
            font-size: 0.86rem;
            color: #9ca3af;
            margin-bottom: 2px;
            min-height: 38px;
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
            border-color: rgba(255, 255, 255, 0.28) !important;
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
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 0 14px 32px rgba(0, 0, 0, 0.5);
            }
            .sidebar.show {
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
    <!-- NAVBAR SUPERIOR ADMINISTRADOR -->
    <nav class="navbar navbar-dark scp-admin-navbar">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <button class="btn scp-menu-toggle text-white p-1" id="scpSidebarToggle" type="button" aria-label="Alternar menú lateral" style="background: none; border: none; font-size: 1.35rem;">
                    <i class="bi bi-list"></i>
                </button>
                <span class="navbar-brand scp-brand-title mb-0 fs-6 fw-bold text-white d-flex align-items-center gap-2">
                    Sistema de Tickets
                </span>
            </div>
            
            <div class="d-flex align-items-center gap-3">
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
        <!-- BARRA LATERAL (SIDEBAR) ADMINISTRADOR -->
        <aside class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-section-title">PANEL ADMIN</div>
                
                <ul class="sidebar-nav">
                    @php
                        $t = request()->query('t', 'pages');
                        $isSettingsTab = in_array($t, ['pages', 'billing', 'tickets', 'tasks', 'agents', 'users']);
                    @endphp

                    <!-- 1. Configuración -->
                    <li class="sidebar-group mb-1">
                        <button type="button" class="sidebar-toggle {{ $isSettingsTab ? 'expanded' : '' }}" data-subnav="admin-settings-subnav" aria-expanded="{{ $isSettingsTab ? 'true' : 'false' }}">
                            <span class="icon"><i class="bi bi-gear"></i></span>
                            <span class="sidebar-label">Configuración</span>
                            <span class="arrow ms-auto">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 5L12 10L7 15" stroke="{{ $isSettingsTab ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <ul id="admin-settings-subnav" class="sidebar-subnav {{ $isSettingsTab ? 'open' : '' }}">
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'pages']) }}" class="sidebar-link {{ $t === 'pages' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-building"></i></span>
                                    <span>Perfil de la empresa</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'billing']) }}" class="sidebar-link {{ $t === 'billing' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-receipt"></i></span>
                                    <span>Facturación</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'tickets']) }}" class="sidebar-link {{ $t === 'tickets' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-ticket-perforated"></i></span>
                                    <span>Solicitudes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'tasks']) }}" class="sidebar-link {{ $t === 'tasks' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-check2-square"></i></span>
                                    <span>Tareas</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'agents']) }}" class="sidebar-link {{ $t === 'agents' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-person-badge"></i></span>
                                    <span>Agentes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'users']) }}" class="sidebar-link {{ $t === 'users' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-people"></i></span>
                                    <span>Usuarios</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 2. Panel de Control (Rojo destacado como en la captura) -->
                    @php
                        $isPanelTab = in_array($t, ['logs', 'audit']);
                    @endphp
                    <li class="sidebar-group my-2">
                        <button type="button" class="sidebar-toggle panel-control-toggle {{ $isPanelTab ? 'expanded' : '' }}" data-subnav="admin-panel-subnav" aria-expanded="{{ $isPanelTab ? 'true' : 'false' }}">
                            <span class="icon icon-panel-control"><i class="bi bi-graph-up"></i></span>
                            <span class="sidebar-label fw-bold">Panel de Control</span>
                            <span class="arrow ms-auto">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 5L12 10L7 15" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <ul id="admin-panel-subnav" class="sidebar-subnav {{ $isPanelTab ? 'open' : '' }}">
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'logs']) }}" class="sidebar-link {{ $t === 'logs' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-terminal"></i></span>
                                    <span>Logs</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'audit']) }}" class="sidebar-link {{ $t === 'audit' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-shield-check"></i></span>
                                    <span>Auditoría</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 3. Notificaciones -->
                    <li class="mb-1">
                        <a href="{{ route('agent.settings.pages', ['t' => 'notifications']) }}" class="sidebar-link {{ $t === 'notifications' ? 'active' : '' }}">
                            <span class="icon"><i class="bi bi-bell"></i></span>
                            <span>Notificaciones</span>
                        </a>
                    </li>

                    <!-- 4. Temas -->
                    <li class="mb-1">
                        <a href="{{ route('agent.settings.pages', ['t' => 'helptopics']) }}" class="sidebar-link {{ $t === 'helptopics' ? 'active' : '' }}">
                            <span class="icon"><i class="bi bi-list-check"></i></span>
                            <span>Temas</span>
                        </a>
                    </li>

                    <!-- 5. Tickets Borrados -->
                    <li class="mb-1">
                        <a href="{{ route('agent.settings.pages', ['t' => 'deleted_tickets']) }}" class="sidebar-link {{ $t === 'deleted_tickets' ? 'active' : '' }}">
                            <span class="icon"><i class="bi bi-trash-fill"></i></span>
                            <span>Tickets Borrados</span>
                        </a>
                    </li>

                    <!-- 6. Correos Electrónicos -->
                    @php
                        $isEmailsTab = in_array($t, ['emails', 'banlist', 'emailtest']);
                    @endphp
                    <li class="sidebar-group mb-1">
                        <button type="button" class="sidebar-toggle {{ $isEmailsTab ? 'expanded' : '' }}" data-subnav="admin-emails-subnav" aria-expanded="{{ $isEmailsTab ? 'true' : 'false' }}">
                            <span class="icon"><i class="bi bi-envelope"></i></span>
                            <span class="sidebar-label">Correos Electrónicos</span>
                            <span class="arrow ms-auto">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 5L12 10L7 15" stroke="{{ $isEmailsTab ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <ul id="admin-emails-subnav" class="sidebar-subnav {{ $isEmailsTab ? 'open' : '' }}">
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'emails']) }}" class="sidebar-link {{ $t === 'emails' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-inbox"></i></span>
                                    <span>Correos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'banlist']) }}" class="sidebar-link {{ $t === 'banlist' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-slash-circle"></i></span>
                                    <span>Lista de prohibidos</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'emailtest']) }}" class="sidebar-link {{ $t === 'emailtest' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-activity"></i></span>
                                    <span>Diagnóstico</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 7. Agentes -->
                    @php
                        $isAgentsTab = in_array($t, ['staff', 'roles', 'departments']);
                    @endphp
                    <li class="sidebar-group mb-1">
                        <button type="button" class="sidebar-toggle {{ $isAgentsTab ? 'expanded' : '' }}" data-subnav="admin-agents-subnav" aria-expanded="{{ $isAgentsTab ? 'true' : 'false' }}">
                            <span class="icon"><i class="bi bi-people"></i></span>
                            <span class="sidebar-label">Agentes</span>
                            <span class="arrow ms-auto">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 5L12 10L7 15" stroke="{{ $isAgentsTab ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <ul id="admin-agents-subnav" class="sidebar-subnav {{ $isAgentsTab ? 'open' : '' }}">
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'staff']) }}" class="sidebar-link {{ $t === 'staff' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-person-badge"></i></span>
                                    <span>Agentes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'roles']) }}" class="sidebar-link {{ $t === 'roles' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-shield-lock"></i></span>
                                    <span>Roles</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.settings.pages', ['t' => 'departments']) }}" class="sidebar-link {{ $t === 'departments' ? 'active' : '' }}">
                                    <span class="icon"><i class="bi bi-diagram-3"></i></span>
                                    <span>Departamentos</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 8. Salir -->
                    <li class="mt-3 pt-2 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                        <form method="POST" action="{{ route('agent.logout') }}" id="admin-logout-form">
                            @csrf
                            <button type="submit" class="sidebar-link w-100 text-start" style="background: none; border: none; cursor: pointer;">
                                <span class="icon"><i class="bi bi-box-arrow-right" style="color: #f87171 !important;"></i></span>
                                <span style="color: #9ca3af;">Salir</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

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
                    const arrowPath = this.querySelector('.arrow svg path');
                    
                    this.classList.toggle('expanded');
                    const isExpanded = this.classList.contains('expanded');
                    this.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
                    
                    if (targetMenu) {
                        targetMenu.classList.toggle('open', isExpanded);
                    }
                    
                    if (arrowPath && !this.classList.contains('panel-control-toggle')) {
                        arrowPath.setAttribute('stroke', isExpanded ? '#ffffff' : '#9ca3af');
                    }
                });
            });

            // Toggle lateral en dispositivos móviles
            const btnMenuMovil = document.getElementById('scpSidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            if (btnMenuMovil && sidebar) {
                btnMenuMovil.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
