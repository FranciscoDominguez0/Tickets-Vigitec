<x-auth.layout title="Acceso de Agentes">
    <div class="welcome-section">
        <h2 class="welcome-title">Portal de Agentes - {{ config('app.name', 'SISTEMA TICKETS') }}</h2>
        <p class="welcome-text">Inicia sesión con tus credenciales de staff para acceder al panel de administración.</p>
    </div>

    <!-- PANEL DE LOGIN -->
    <div class="login-panel login-panel-split">
        <!-- COLUMNA IZQUIERDA - FORMULARIO -->
        <div class="login-panel-left">
            <div class="login-form-header">
                <h2 class="login-form-title">Acceso de Personal</h2>
                <p class="login-form-subtitle">Ingresa tus credenciales de agente para continuar.</p>
            </div>
            
            <form method="POST" action="{{ route('agent.login') }}" class="login-form">
                @csrf

                <!-- Mensajes de Estado/Error Global -->
                @if (session('status'))
                    <x-auth.alert type="success" :message="session('status')" />
                @endif
                @if ($errors->any())
                    <x-auth.alert type="danger" message="Error de autenticación. Revisa tus credenciales." />
                @endif

                <!-- Email o Username (en el original era username o email, ahora email) -->
                <x-auth.input 
                    id="email" 
                    type="email" 
                    name="email" 
                    label="Correo electrónico o Usuario" 
                    placeholder="Correo de agente" 
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
                    <a href="#" class="register-link">¿Olvidaste tu contraseña?</a>
                </div>

                <!-- Botón Login -->
                <x-auth.button>Entrar al Panel</x-auth.button>

                <div class="login-side-links" style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    <p class="register-text">
                        ¿Eres un cliente?
                        <a href="{{ route('login') }}" class="register-link">Ir al portal de clientes</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- COLUMNA DERECHA - ENLACES E ICONO -->
        <div class="login-panel-right login-panel-right-center" style="background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);">
            <div class="login-corner-mark" aria-hidden="true">
                <span class="login-corner-dot"></span>
                <span class="login-corner-text">
                    <span class="login-corner-text-top">PANEL</span>
                    <span class="login-corner-text-bottom">AGENTES</span>
                </span>
            </div>
            <div class="login-welcome">
                <h2 class="login-welcome-title">Staff <span>Tickets</span></h2>
                <p class="login-welcome-text">Plataforma de gestión de incidencias y atención a clientes.</p>
        </div>
    </div>
</x-auth.layout>
