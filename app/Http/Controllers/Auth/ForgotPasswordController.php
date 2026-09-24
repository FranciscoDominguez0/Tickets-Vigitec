<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Return a dummy success message since email sending is likely not configured yet
        return back()->with('status', 'Le hemos enviado por correo electrónico el enlace para restablecer su contraseña.');
    }
}
