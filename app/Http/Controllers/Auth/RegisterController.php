<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'lat'       => 'nullable|numeric',
            'lng'       => 'nullable|numeric',
            // 'empresa_id' => 'required|integer' // Adjust depending on real logic
        ]);

        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname'  => $validated['lastname'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'empresa_id' => 1, // Default dummy for now
            'status'    => 1,
            // Assuming the table has lat/lng or similar
        ]);

        Auth::guard('web')->login($user);

        return redirect()->route('client.dashboard')->with('success', 'Cuenta creada exitosamente.');
    }
}
