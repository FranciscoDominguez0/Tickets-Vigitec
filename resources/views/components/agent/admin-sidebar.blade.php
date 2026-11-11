@php
    $esGrupoConfiguracion = request()->routeIs('admin.settings.*');
    $esGrupoPanel = request()->routeIs('admin.logs', 'admin.audit');
    $esGrupoCorreos = request()->routeIs('admin.email', 'admin.banlist', 'admin.emailtest');
    $esGrupoPersonal = request()->routeIs('admin.staff', 'admin.roles', 'admin.roles.*', 'admin.departments');
@endphp

<!-- BARRA LATERAL (SIDEBAR) ADMINISTRADOR INDEPENDIENTE -->
<aside class="sidebar admin-sidebar-panel">
    <!-- Encabezado de la barra lateral con resplandor superior rojo -->
    <div class="sidebar-header d-flex align-items-center gap-2 px-3 pt-3 pb-2">
        <button type="button" class="sidebar-brand-btn" id="adminSidebarToggleBtn" aria-label="Alternar menú">
            <i class="bi bi-dash-lg"></i>
        </button>
        <span class="sidebar-brand-text">Sistema de Tickets</span>
    </div>

    <!-- Sección principal de navegación -->
    <div class="sidebar-section px-2 mt-2">
        <div class="sidebar-section-title">PANEL ADMIN</div>
        
        <ul class="sidebar-nav">
            <!-- 1. Configuración -->
            <li class="sidebar-group mb-1">
                <button type="button" class="sidebar-toggle {{ $esGrupoConfiguracion ? 'expanded' : '' }}" data-subnav="admin-settings-subnav" aria-expanded="{{ $esGrupoConfiguracion ? 'true' : 'false' }}">
                    <span class="sidebar-icon"><i class="bi bi-gear"></i></span>
                    <span class="sidebar-label">Configuración</span>
                    <span class="arrow ms-auto">
                        <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 5L12 10L7 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
                <ul id="admin-settings-subnav" class="sidebar-subnav {{ $esGrupoConfiguracion ? 'open' : '' }}">
                    <li>
                        <a href="{{ route('admin.settings.pages') }}" class="sidebar-link {{ request()->routeIs('admin.settings.pages') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-building"></i></span>
                            <span>Perfil de la empresa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.billing') }}" class="sidebar-link {{ request()->routeIs('admin.settings.billing') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-receipt"></i></span>
                            <span>Facturación</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.tickets') }}" class="sidebar-link {{ request()->routeIs('admin.settings.tickets') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-ticket-perforated"></i></span>
                            <span>Solicitudes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.tasks') }}" class="sidebar-link {{ request()->routeIs('admin.settings.tasks') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-check2-square"></i></span>
                            <span>Tareas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.agents') }}" class="sidebar-link {{ request()->routeIs('admin.settings.agents') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-person-badge"></i></span>
                            <span>Agentes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.users') }}" class="sidebar-link {{ request()->routeIs('admin.settings.users') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-people"></i></span>
                            <span>Usuarios</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 2. Panel de Control (Rojo destacado como en la captura) -->
            <li class="sidebar-group my-2">
                <button type="button" class="sidebar-toggle panel-control-toggle {{ $esGrupoPanel ? 'expanded' : '' }}" data-subnav="admin-panel-subnav" aria-expanded="{{ $esGrupoPanel ? 'true' : 'false' }}">
                    <span class="sidebar-icon icon-panel-control"><i class="bi bi-graph-up"></i></span>
                    <span class="sidebar-label fw-bold">Panel de Control</span>
                    <span class="arrow ms-auto">
                        <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 5L12 10L7 15" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
                <ul id="admin-panel-subnav" class="sidebar-subnav {{ $esGrupoPanel ? 'open' : '' }}">
                    <li>
                        <a href="{{ route('admin.logs') }}" class="sidebar-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-terminal"></i></span>
                            <span>Logs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.audit') }}" class="sidebar-link {{ request()->routeIs('admin.audit') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-shield-check"></i></span>
                            <span>Auditoría</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 3. Notificaciones -->
            <li class="mb-1">
                <a href="{{ route('admin.notifications') }}" class="sidebar-link {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="bi bi-bell"></i></span>
                    <span>Notificaciones</span>
                </a>
            </li>

            <!-- 4. Temas -->
            <li class="mb-1">
                <a href="{{ route('admin.helptopics') }}" class="sidebar-link {{ request()->routeIs('admin.helptopics') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="bi bi-list-ul"></i></span>
                    <span>Temas</span>
                </a>
            </li>

            <!-- 5. Tickets Borrados -->
            <li class="mb-1">
                <a href="{{ route('admin.deleted_tickets') }}" class="sidebar-link {{ request()->routeIs('admin.deleted_tickets') ? 'active' : '' }}">
                    <span class="sidebar-icon"><i class="bi bi-trash3"></i></span>
                    <span>Tickets Borrados</span>
                </a>
            </li>

            <!-- 6. Correos Electrónicos -->
            <li class="sidebar-group mb-1">
                <button type="button" class="sidebar-toggle {{ $esGrupoCorreos ? 'expanded' : '' }}" data-subnav="admin-emails-subnav" aria-expanded="{{ $esGrupoCorreos ? 'true' : 'false' }}">
                    <span class="sidebar-icon"><i class="bi bi-envelope"></i></span>
                    <span class="sidebar-label">Correos Electrónicos</span>
                    <span class="arrow ms-auto">
                        <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 5L12 10L7 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
                <ul id="admin-emails-subnav" class="sidebar-subnav {{ $esGrupoCorreos ? 'open' : '' }}">
                    <li>
                        <a href="{{ route('admin.email') }}" class="sidebar-link {{ request()->routeIs('admin.email') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-inbox"></i></span>
                            <span>Correos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.banlist') }}" class="sidebar-link {{ request()->routeIs('admin.banlist') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-slash-circle"></i></span>
                            <span>Lista de prohibidos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.emailtest') }}" class="sidebar-link {{ request()->routeIs('admin.emailtest') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-activity"></i></span>
                            <span>Diagnóstico</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 7. Agentes -->
            <li class="sidebar-group mb-1">
                <button type="button" class="sidebar-toggle {{ $esGrupoPersonal ? 'expanded' : '' }}" data-subnav="admin-agents-subnav" aria-expanded="{{ $esGrupoPersonal ? 'true' : 'false' }}">
                    <span class="sidebar-icon"><i class="bi bi-people"></i></span>
                    <span class="sidebar-label">Agentes</span>
                    <span class="arrow ms-auto">
                        <svg width="12" height="12" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 5L12 10L7 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </button>
                <ul id="admin-agents-subnav" class="sidebar-subnav {{ $esGrupoPersonal ? 'open' : '' }}">
                    <li>
                        <a href="{{ route('admin.staff') }}" class="sidebar-link {{ request()->routeIs('admin.staff') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-person-badge"></i></span>
                            <span>Agentes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles', 'admin.roles.*') }}" class="sidebar-link {{ request()->routeIs('admin.roles', 'admin.roles.*') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-shield-lock"></i></span>
                            <span>Roles</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.departments') }}" class="sidebar-link {{ request()->routeIs('admin.departments') ? 'active' : '' }}">
                            <span class="sidebar-icon"><i class="bi bi-diagram-3"></i></span>
                            <span>Departamentos</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- 8. Salir -->
            <li class="mt-3 pt-2 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                <form method="POST" action="{{ route('agent.logout') }}" id="admin-logout-sidebar-form">
                    @csrf
                    <button type="submit" class="sidebar-link w-100 text-start" style="background: none; border: none; cursor: pointer;">
                        <span class="sidebar-icon"><i class="bi bi-box-arrow-right" style="color: #f87171 !important;"></i></span>
                        <span style="color: #9ca3af;">Salir</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>

