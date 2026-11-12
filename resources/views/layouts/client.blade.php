<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal de Soporte' }} - {{ config('app.name', 'Vigitec') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #ef4444;
            --primary-hover: #dc2626;
            --primary-subtle: #fef2f2;
            --primary-border: #fca5a5;
            --surface-bg: #f8fafc;
            --card-border: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Instrument Sans', system-ui, -apple-system, sans-serif;
            background-color: var(--surface-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .client-navbar {
            background: linear-gradient(135deg, #0b1220 0%, #111827 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.15rem;
            color: #ffffff !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.5px;
        }

        .brand-accent {
            color: #ef4444;
            font-style: italic;
        }

        .nav-link-custom {
            color: #cbd5e1 !important;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .btn-crear-ticket {
            background-color: #ef4444;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.88rem;
            border-radius: 999px;
            padding: 0.45rem 1.1rem;
            border: 1px solid #ef4444;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            text-decoration: none;
        }

        .btn-crear-ticket:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
        }

        .user-avatar-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            color: #f8fafc;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .user-avatar-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .avatar-initials {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .profile-dropdown-menu {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            padding: 0.5rem;
            min-width: 220px;
        }

        .profile-dropdown-menu .dropdown-item {
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 0.5rem 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #334155;
        }

        .profile-dropdown-menu .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .profile-dropdown-menu .dropdown-item.text-danger:hover {
            background-color: #fef2f2;
            color: #dc2626 !important;
        }

        /* Alerts & Main Shell */
        .main-shell {
            flex: 1;
            padding: 2rem 0 3rem;
        }

        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            margin-bottom: 1.5rem;
        }

        .alert-custom.alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-custom.alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1.25rem 0;
            font-size: 0.85rem;
            color: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Barra Superior / Navbar -->
    <nav class="client-navbar sticky-top">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-4">
                <a class="navbar-brand-custom" href="{{ route('dashboard') }}">
                    <span class="brand-accent">//</span>
                    <span>{{ config('app.name', 'VIGITEC') }}</span>
                    <span class="badge bg-secondary text-uppercase ms-1" style="font-size: 0.65rem; letter-spacing: 0.05em; background: rgba(255,255,255,0.1) !important;">Portal Clientes</span>
                </a>

                <div class="d-none d-md-flex align-items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard', 'client.tickets.*') ? 'active' : '' }}">
                        <i class="bi bi-inbox"></i> Mis Tickets
                    </a>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('client.tickets.create') }}" class="btn-crear-ticket">
                    <i class="bi bi-plus-lg"></i>
                    <span class="d-none d-sm-inline">Abrir Ticket</span>
                </a>

                @auth
                    @php
                        $usuarioAuth = auth()->user();
                        $iniciales = $usuarioAuth->iniciales ?? 'U';
                        $nombreCorto = $usuarioAuth->firstname ? ($usuarioAuth->firstname . ' ' . mb_substr($usuarioAuth->lastname ?? '', 0, 1) . '.') : ($usuarioAuth->email ?? 'Usuario');
                    @endphp
                    <div class="dropdown">
                        <button class="user-avatar-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar-initials">{{ $iniciales }}</span>
                            <span class="d-none d-md-inline">{{ $nombreCorto }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                            <li class="px-3 py-2 border-bottom mb-1">
                                <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $usuarioAuth->nombre_completo }}</div>
                                <div class="text-muted" style="font-size: 0.78rem;">{{ $usuarioAuth->email }}</div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-inbox text-secondary"></i> Mis Tickets
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('client.tickets.create') }}">
                                    <i class="bi bi-plus-circle text-secondary"></i> Nuevo Ticket
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger w-100 border-0 bg-transparent text-start">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenedor Principal -->
    <main class="main-shell">
        <div class="container">
            <!-- Alertas Flash -->
            @if(session('success'))
                <div class="alert alert-custom alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-custom alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-custom alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                    <div class="flex-grow-1">
                        <strong>Por favor verifica los siguientes campos:</strong>
                        <ul class="mb-0 mt-1 ps-3" style="font-size: 0.88rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </div>
    </main>

    <!-- Pie de página -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Vigitec') }}. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

