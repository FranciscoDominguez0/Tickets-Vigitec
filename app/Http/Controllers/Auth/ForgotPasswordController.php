<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes ingresar un correo electrónico válido.',
        ]);

        // Usamos el Password broker nativo de Laravel para enviar el enlace al correo
        Password::sendResetLink(
            $request->only('email')
        );

        // Por seguridad, siempre devolvemos el mismo mensaje para evitar enumeración de correos
        return back()->with(['status' => 'Si el correo existe en nuestro sistema, te hemos enviado un enlace para restablecer tu contraseña.']);
    }
}
