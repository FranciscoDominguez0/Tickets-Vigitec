<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Agentes - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS (from Laravel Vite or public folder) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS from legacy -->
    <link rel="stylesheet" href="{{ asset('css/scp/scp.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scp/dark.css') }}">
    
    @stack('styles')
</head>
<body class="dark-mode"> <!-- Assume dark mode by default based on legacy preference -->
    <div class="layout">
        <!-- SIDEBAR AGENTES -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="sidebar-brand-logo text-center w-100 mt-2">
                    <h5 class="text-white mb-0" style="font-weight: 800; font-style: italic; letter-spacing: 1px;">
                        <span style="color: #ef4444;">//</span> VIGITEC PANAMA
                    </h5>
                </div>
                <span class="sidebar-brand-collapsed-mark" aria-hidden="true">//</span>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-section-title">PRINCIPAL</div>
                <ul class="sidebar-nav">
                    <!-- Panel de control -->
                    <li class="sidebar-group">
                        @php
                            $isPanelRoute = request()->routeIs('agent.dashboard', 'agent.directory', 'agent.map');
                        @endphp
                        <button type="button" class="sidebar-link sidebar-toggle {{ $isPanelRoute ? 'expanded' : '' }}" data-subnav="panel-subnav" aria-expanded="{{ $isPanelRoute ? 'true' : 'false' }}">
                            <span class="icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 12L11 5L18 12V19H4V12Z" stroke="{{ $isPanelRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            Panel de control
                            <span class="arrow">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 5L12 10L7 15" stroke="{{ $isPanelRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <ul id="panel-subnav" class="sidebar-subnav {{ $isPanelRoute ? 'open' : '' }}">
                            <li>
                                <a href="{{ route('agent.dashboard') }}" class="sidebar-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="4" y="4" width="16" height="16" rx="3" stroke="{{ request()->routeIs('agent.dashboard') ? '#ffffff' : '#64748b' }}" stroke-width="1.6"/>
                                            <path d="M9 12L11 14L15 10" stroke="{{ request()->routeIs('agent.dashboard') ? '#ffffff' : '#64748b' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    Resumen
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.directory') }}" class="sidebar-link {{ request()->routeIs('agent.directory') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5 5H14L19 10V19H5V5Z" stroke="{{ request()->routeIs('agent.directory') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M9 13H15" stroke="{{ request()->routeIs('agent.directory') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    Directorio del agente
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.map') }}" class="sidebar-link {{ request()->routeIs('agent.map') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" stroke="{{ request()->routeIs('agent.map') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                            <circle cx="12" cy="9" r="2.5" stroke="{{ request()->routeIs('agent.map') ? '#ffffff' : '#64748b' }}" stroke-width="1.6"/>
                                        </svg>
                                    </span>
                                    Mapa de agentes
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Tickets -->
                    <li class="sidebar-group">
                        @php
                            $isTicketsRoute = request()->routeIs('agent.tickets.*');
                        @endphp
                        <button type="button" class="sidebar-link sidebar-toggle {{ $isTicketsRoute ? 'expanded' : '' }}" data-subnav="tickets-subnav" aria-expanded="{{ $isTicketsRoute ? 'true' : 'false' }}">
                            <span class="icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="4" y="4" width="16" height="16" rx="2" stroke="{{ $isTicketsRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8"/>
                                    <path d="M8 8H16" stroke="{{ $isTicketsRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M8 13H13" stroke="{{ $isTicketsRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </span>
                            Tickets
                            <span class="arrow">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 5L12 10L7 15" stroke="{{ $isTicketsRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <ul id="tickets-subnav" class="sidebar-subnav {{ $isTicketsRoute ? 'open' : '' }}">
                            <li>
                                <a href="{{ route('agent.tickets.index') }}" class="sidebar-link {{ request()->routeIs('agent.tickets.index') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="2" y="4" width="20" height="16" rx="2" stroke="{{ request()->routeIs('agent.tickets.index') ? '#ffffff' : '#64748b' }}" stroke-width="1.6"/>
                                            <path d="M7 9H17M7 14H13" stroke="{{ request()->routeIs('agent.tickets.index') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    Detalles
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.tickets.billing') }}" class="sidebar-link {{ request()->routeIs('agent.tickets.billing') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="9" stroke="{{ request()->routeIs('agent.tickets.billing') ? '#ffffff' : '#64748b' }}" stroke-width="1.6"/>
                                            <path d="M12 7v5l3 2" stroke="{{ request()->routeIs('agent.tickets.billing') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    Por facturar
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.tickets.report_sheet') }}" class="sidebar-link {{ request()->routeIs('agent.tickets.report_sheet') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 19v-4m4 4v-8m4 8v-6m4 6v-10" stroke="{{ request()->routeIs('agent.tickets.report_sheet') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    Hoja de reporte
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Reporte -->
                    <li>
                        <a href="{{ route('agent.reports.index') }}" class="sidebar-link {{ request()->routeIs('agent.reports.index') ? 'active' : '' }}">
                            <span class="icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 19v-4m4 4v-8m4 8v-6m4 6v-10" stroke="{{ request()->routeIs('agent.reports.index') ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            Reporte
                        </a>
                    </li>

                    <!-- Inventario -->
                    <li>
                        <a href="{{ route('agent.inventory.index') }}" class="sidebar-link {{ request()->routeIs('agent.inventory.index') ? 'active' : '' }}">
                            <span class="icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 8l8-4 8 4M4 8v8l8 4M4 8l8 4M20 8v8l-8 4M20 8l-8 4M12 12v8" stroke="{{ request()->routeIs('agent.inventory.index') ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            Inventario
                        </a>
                    </li>

                    <!-- Usuarios -->
                    <li class="sidebar-group">
                        @php
                            $isUsersRoute = request()->routeIs('agent.users.*');
                        @endphp
                        <button type="button" class="sidebar-link sidebar-toggle {{ $isUsersRoute ? 'expanded' : '' }}" data-subnav="users-subnav" aria-expanded="{{ $isUsersRoute ? 'true' : 'false' }}">
                            <span class="icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="9" cy="8" r="3" stroke="{{ $isUsersRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8"/>
                                    <path d="M4 19C4.6 16 6.5 14.5 9 14.5C11.5 14.5 13.4 16 14 19" stroke="{{ $isUsersRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round"/>
                                    <circle cx="17" cy="8" r="2.5" stroke="{{ $isUsersRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.6"/>
                                </svg>
                            </span>
                            Usuarios
                            <span class="arrow">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 5L12 10L7 15" stroke="{{ $isUsersRoute ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </button>
                        <ul id="users-subnav" class="sidebar-subnav {{ $isUsersRoute ? 'open' : '' }}">
                            <li>
                                <a href="{{ route('agent.users.directory') }}" class="sidebar-link {{ request()->routeIs('agent.users.directory') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="9" cy="8" r="2.5" stroke="{{ request()->routeIs('agent.users.directory') ? '#ffffff' : '#64748b' }}" stroke-width="1.6"/>
                                            <path d="M4 19C4.6 16 6.5 14.5 9 14.5C11.5 14.5 13.4 16 14 19" stroke="{{ request()->routeIs('agent.users.directory') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round"/>
                                            <circle cx="17" cy="8" r="2" stroke="{{ request()->routeIs('agent.users.directory') ? '#ffffff' : '#64748b' }}" stroke-width="1.6"/>
                                        </svg>
                                    </span>
                                    Directorio usuarios
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agent.users.orgs') }}" class="sidebar-link {{ request()->routeIs('agent.users.orgs') ? 'active' : '' }}">
                                    <span class="icon">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="4" y="8" width="16" height="10" rx="2" stroke="{{ request()->routeIs('agent.users.orgs') ? '#ffffff' : '#64748b' }}" stroke-width="1.6"/>
                                            <path d="M9 8V6C9 4.89543 9.89543 4 11 4H13C14.1046 4 15 4.89543 15 6V8" stroke="{{ request()->routeIs('agent.users.orgs') ? '#ffffff' : '#64748b' }}" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    Organizaciones
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Mi Perfil -->
                    <li>
                        <a href="{{ route('agent.profile.index') }}" class="sidebar-link {{ request()->routeIs('agent.profile.index') ? 'active' : '' }}">
                            <span class="icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="8" r="3" stroke="{{ request()->routeIs('agent.profile.index') ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8"/>
                                    <path d="M6 19C6.6 16.5 8.8 15 12 15C15.2 15 17.4 16.5 18 19" stroke="{{ request()->routeIs('agent.profile.index') ? '#ffffff' : '#9ca3af' }}" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </span>
                            Mi perfil
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-section mt-auto">
                <ul class="sidebar-nav">
                    <li>
                        <form method="POST" action="{{ route('agent.logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="sidebar-link w-100 text-start" style="background: none; border: none; cursor: pointer;">
                                <span class="icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 5H5V19H10" stroke="#f87171" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M15 9L19 12L15 15" stroke="#f87171" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M19 12H9" stroke="#f87171" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                Salir
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- TOP NAVBAR -->
            <nav class="navbar navbar-expand-lg border-bottom px-3 py-2" style="background-color: var(--card-bg, #1e293b); border-color: rgba(255,255,255,0.1) !important;">
                <div class="container-fluid">
                    <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" id="mobileSidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <span class="me-3 text-white">Hola, {{ auth('staff')->user()->firstname ?? 'Agente' }}</span>
                    </div>
                </div>
            </nav>

            <div class="content-padding p-4">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Submenus
            const toggles = document.querySelectorAll('.sidebar-toggle');
            toggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-subnav');
                    const targetMenu = document.getElementById(targetId);
                    const arrow = this.querySelector('svg path');
                    
                    // Toggle clases para animación del botón
                    this.classList.toggle('expanded');
                    
                    if (this.classList.contains('expanded')) {
                        arrow.setAttribute('stroke', '#ffffff');
                    } else {
                        // Check if it's currently the active route to keep the icon white
                        if (this.classList.contains('active')) {
                            arrow.setAttribute('stroke', '#ffffff');
                        } else {
                            arrow.setAttribute('stroke', '#9ca3af');
                        }
                    }

                    // Toggle menú
                    if(targetMenu) {
                        targetMenu.classList.toggle('open');
                    }
                });
            });

            // Mobile Sidebar Toggle
            const mobileBtn = document.getElementById('mobileSidebarToggle');
            if (mobileBtn) {
                mobileBtn.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    if(sidebar) {
                        if(sidebar.style.transform === 'translateX(0px)') {
                            sidebar.style.transform = 'translateX(-100%)';
                        } else {
                            sidebar.style.transform = 'translateX(0px)';
                        }
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
