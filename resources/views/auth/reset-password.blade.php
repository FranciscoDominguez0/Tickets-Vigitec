<x-auth.layout title="Restablecer contraseña">
    <!-- PANEL DE RESTABLECIMIENTO -->
    <div class="login-panel login-panel-split">
        <!-- COLUMNA IZQUIERDA - FORMULARIO -->
        <div class="login-panel-left">
            <div class="login-form-header">
                <h2 class="login-form-title">Restablecer contraseña</h2>
                <p class="login-form-subtitle">Ingresa tu nueva contraseña a continuación.</p>
            </div>
            
            <form method="POST" action="{{ route('password.update') }}" class="login-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Alertas -->
                @if ($errors->any())
                    <x-auth.alert type="danger" :message="$errors->first()" />
                @endif

                <!-- Correo electrónico (Oculto, pero requerido por seguridad) -->
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <!-- Nueva Contraseña -->
                <x-auth.input 
                    id="password" 
                    type="password" 
                    name="password" 
                    label="Nueva contraseña" 
                    required 
                />

                <!-- Confirmar Contraseña -->
                <x-auth.input 
                    id="password_confirmation" 
                    type="password" 
                    name="password_confirmation" 
                    label="Confirmar contraseña" 
                    required 
                />

                <!-- Botón -->
                <x-auth.button>Restablecer contraseña</x-auth.button>
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
                <h2 class="login-welcome-title">Casi listo</h2>
                <p class="login-welcome-text">Solo ingresa tu nueva contraseña para volver a acceder a tu cuenta.</p>
            </div>
        </div>
    </div>
</x-auth.layout>
