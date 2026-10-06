<?php

namespace App\Http\Controllers\Agent\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login para agentes.
     */
    public function mostrarFormularioLogin()
    {
        return view('agent.auth.login');
    }

    /**
     * Procesa la solicitud de inicio de sesión de agentes.
     */
    public function iniciarSesion(Request $request)
    {
        // Validación de datos
        // El sistema original permitía login por email o username, usaremos email o ajustaremos a username.
        // La tabla staff tiene `email` y `username`.
        // Asumiremos que entran con correo por ahora.
        $credenciales = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'El usuario es requerido.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        // Intentar autenticar usando el guard 'staff'
        if (Auth::guard('staff')->attempt(['username' => $credenciales['username'], 'password' => $credenciales['password'], 'is_active' => 1])) {
            $request->session()->regenerate();

            // Redirigir al dashboard de agente
            return redirect()->intended('agent/dashboard');
        }

        // Si falla, volver con error
        return back()->withErrors([
            'username' => 'Acceso denegado. Verifica tus credenciales o el estado de tu cuenta.',
        ])->onlyInput('username');
    }

    /**
     * Cierra la sesión del agente.
     */
    public function cerrarSesion(Request $request)
    {
        Auth::guard('staff')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/agent/login');
    }
}
