<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} - {{ config('app.name') }}</title>
    <!-- CSS del Login original -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/client_dark.css') }}">
</head>
<body class="dark-mode"> <!-- Por defecto en oscuro como lo prefieren -->
    <div class="support-center-wrapper">
        <!-- HEADER SUPERIOR -->
        <div class="support-header">
            <div class="support-header-left">
                <!-- Nombre del Sistema o Logo -->
                <img src="{{ asset('publico/img/vigitec-logo.webp') }}" alt="VIGITEC PANAMA" class="vigitec-logo">
            </div>
            <div class="support-header-right d-flex align-items-center gap-3">
                <button type="button" id="loginDarkModeBtn" class="btn btn-outline-secondary btn-sm" style="border-radius:999px; width:34px; height:34px; padding:0; display:inline-flex; align-items:center; justify-content:center; border-color: rgba(255,255,255,0.15);" title="Alternar modo oscuro">
                    <i class="bi bi-moon-stars" style="font-size:16px;"></i>
                </button>
                <a href="{{ route('login') }}" class="header-login-link">Inicia Sesión</a>
            </div>
        </div>

        <!-- NAVEGACIÓN -->
        <div class="support-nav">
            <button class="nav-item active">Inicio Centro de Soporte</button>
        </div>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="support-content">
            {{ $slot }}
        </div>

        <!-- FOOTER -->
        <div class="support-footer">
            <p class="copyright">
                Derechos de autor &copy; {{ date('Y') }} {{ config('app.name') }} - Todos los derechos reservados.
            </p>
        </div>
    </div>
    
    <!-- Script para Modo Oscuro y prevenir doble envío -->
    <script>
        // Evitar submit duplicado en los formularios
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                if (btn) {
                    if (btn.disabled) {
                        e.preventDefault();
                        return false;
                    }
                    btn.disabled = true;
                    btn.classList.add('loading');
                    btn.textContent = 'Procesando...';
                }
            });
        });

        // Alternar modo oscuro
        var loginDarkBtn = document.getElementById('loginDarkModeBtn');
        if (loginDarkBtn) {
            loginDarkBtn.addEventListener('click', function() {
                var isDark = document.body.classList.contains('dark-mode');
                if (isDark) {
                    document.body.classList.remove('dark-mode');
                } else {
                    document.body.classList.add('dark-mode');
                }
                
                var icon = this.querySelector('i');
                if (icon) {
                    icon.className = isDark ? 'bi bi-moon-stars' : 'bi bi-sun';
                }
            });
        }
    </script>
</body>
</html>
