<x-auth.layout title="Iniciar sesión">
    <div class="welcome-section">
        <h2 class="welcome-title">Iniciar sesión en {{ config('app.name', 'SISTEMA TICKETS') }}</h2>
        <p class="welcome-text">Para servirle mejor, recomendamos a nuestros clientes registrarse para una cuenta.</p>
    </div>

    <!-- PANEL DE LOGIN -->
    <div class="login-panel login-panel-split">
        <!-- COLUMNA IZQUIERDA - FORMULARIO -->
        <div class="login-panel-left">
            <div class="login-form-header">
                <h2 class="login-form-title">Inicia sesión</h2>
                <p class="login-form-subtitle">Accede a tu cuenta para gestionar tus solicitudes.</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}" class="login-form">
                @csrf

                <!-- Mensajes de Estado/Error Global -->
                @if (session('status'))
                    <x-auth.alert type="success" :message="session('status')" />
                @endif
                @if (session('error'))
                    <x-auth.alert type="danger" :message="session('error')" />
                @endif

                <!-- Email -->
                <x-auth.input 
                    id="email" 
                    type="email" 
                    name="email" 
                    label="Correo electrónico" 
                    placeholder="Correo electrónico" 
                    required 
                />

                <!-- Contraseña -->
                <x-auth.input 
                    id="password" 
                    type="password" 
                    name="password" 
                    label="Contraseña" 
                    placeholder="Contraseña" 
                    required 
                />

                <div class="login-forgot">
                    <!-- TODO: Enlace a restablecer contraseña -->
                    <a href="#" class="register-link">Olvidé mi contraseña</a>
                </div>

                <!-- Botón Login -->
                <x-auth.button>Inicia Sesión</x-auth.button>

                <div class="login-side-links" style="margin-top: 1.5rem; display: flex; justify-content: space-between;">
                    <p class="register-text">
                        ¿Sin cuenta?
                        <!-- TODO: Enlace a registro -->
                        <a href="#" class="register-link">Crear cuenta</a>
                    </p>
                    <p class="agent-text">
                        ¿Eres agente?
                        <!-- TODO: Enlace a login de agentes -->
                        <a href="{{ route('agent.login') }}" class="agent-link">Entrar</a>
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
                <h2 class="login-welcome-title">Hola, <span>¡bienvenido!</span></h2>
                <p class="login-welcome-text">Inicia sesión para crear y dar seguimiento a tus solicitudes. Estamos aquí para ayudarte.</p>
        </div>
    </div>
</x-auth.layout>
