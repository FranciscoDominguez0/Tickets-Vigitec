<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function mostrarFormularioLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesa la solicitud de inicio de sesión.
     */
    public function iniciarSesion(Request $request)
    {
        // Validación de datos
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'Debes proporcionar un correo electrónico válido.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        // Intentar autenticar
        // Asegúrate de que el modelo User esté configurado con el campo "password" correcto.
        if (Auth::attempt(['email' => $credenciales['email'], 'password' => $credenciales['password']])) {
            $request->session()->regenerate();

            // Redirigir al dashboard/panel principal
            return redirect()->intended('dashboard');
        }

        // Si falla, volver con error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function cerrarSesion(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
