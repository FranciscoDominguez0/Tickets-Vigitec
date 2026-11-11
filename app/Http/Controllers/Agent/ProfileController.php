<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\ActualizarPerfilRequest;
use App\Http\Requests\Agent\CambiarContrasenaRequest;
use App\Models\Staff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Muestra la vista del perfil del agente autenticado.
     */
    public function mostrar(): View
    {
        /** @var Staff $agente */
        $agente = auth('staff')->user()->load('department');

        $totalTickets = $agente->tickets()->count();
        $ticketsAbiertos = $agente->tickets()->where('status_id', '!=', 5)->count();

        return view('agent.profile.index', compact('agente', 'totalTickets', 'ticketsAbiertos'));
    }

    /**
     * Actualiza la información básica y preferencias del perfil.
     */
    public function actualizar(ActualizarPerfilRequest $solicitud): RedirectResponse
    {
        /** @var Staff $agente */
        $agente = auth('staff')->user();

        $datos = $solicitud->validated();
        $datos['dark_mode'] = (bool) ($solicitud->input('dark_mode', 0));

        $agente->update($datos);

        return redirect()->route('agent.profile.index')->with('exito', 'Perfil actualizado correctamente.');
    }

    /**
     * Actualiza la contraseña de acceso del agente.
     */
    public function cambiarContrasena(CambiarContrasenaRequest $solicitud): RedirectResponse
    {
        /** @var Staff $agente */
        $agente = auth('staff')->user();

        $agente->update([
            'password' => Hash::make($solicitud->validated('password')),
        ]);

        return redirect()->route('agent.profile.index')->with('exito', 'Contraseña actualizada correctamente.');
    }
}
