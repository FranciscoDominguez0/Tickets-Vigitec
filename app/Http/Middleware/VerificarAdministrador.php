<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarAdministrador
{
    /**
     * Verifica que el usuario autenticado en staff tenga permisos de administrador.
     */
    public function handle(Request $solicitud, Closure $siguiente): Response
    {
        if (! auth('staff')->check() || ! auth('staff')->user()->esAdmin()) {
            return redirect()->route('agent.dashboard')->with('error', 'Acceso denegado. Se requieren permisos de administrador.');
        }

        return $siguiente($solicitud);
    }
}
