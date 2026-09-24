<x-auth.layout title="Recuperar contraseña">
    <!-- PANEL DE RECUPERACIÓN -->
    <div class="login-panel login-panel-split">
        <!-- COLUMNA IZQUIERDA - FORMULARIO -->
        <div class="login-panel-left">
            <div class="login-form-header">
                <h2 class="login-form-title">Recuperar contraseña</h2>
                <p class="login-form-subtitle">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>
            </div>
            
            <form method="POST" action="{{ route('password.email') }}" class="login-form">
                @csrf

                <!-- Alertas -->
                @if (session('status'))
                    <x-auth.alert type="success" :message="session('status')" />
                @endif
                @if ($errors->any())
                    <x-auth.alert type="danger" :message="$errors->first()" />
                @endif

                <!-- Correo electrónico -->
                <x-auth.input 
                    id="email" 
                    type="email" 
                    name="email" 
                    label="Correo electrónico" 
                    placeholder="" 
                    value="{{ old('email') }}"
                    required 
                />

                <!-- Botón -->
                <x-auth.button>Enviar enlace</x-auth.button>

                <div class="login-side-links" style="justify-content: flex-start; margin-top: 16px;">
                    <p class="register-text" style="color: #94a3b8; font-size: 14px;">
                        ¿Recordaste tu contraseña?
                        <a href="{{ route('login') }}" style="color: #3b82f6; text-decoration: none; font-weight: 500;">Iniciar sesión</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- COLUMNA DERECHA - ENLACES E ICONO -->
        <div class="login-panel-right login-panel-right-center">
            <div class="login-corner-mark" aria-hidden="true">
                <span class="login-corner-dot"></span>
                <span class="login-corner-text">
                    <span class="login-corner-text-top">SISTEMA</span>
                    <span class="login-corner-text-bottom">TICKETS</span>
                </span>
            </div>
            <div class="login-welcome">
                <h2 class="login-welcome-title">Estás a un paso<br>de volver</h2>
                <p class="login-welcome-text">Sigue las instrucciones y en minutos tendrás acceso<br>otra vez.</p>
            </div>
        </div>
    </div>
</x-auth.layout>
