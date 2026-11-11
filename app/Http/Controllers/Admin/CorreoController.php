<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CorreoController extends Controller
{
    /**
     * Muestra la vista en blanco de cuentas de correo electrónico.
     * Ruta: /admin/email
     */
    public function listar(Request $solicitud): View
    {
        return view('admin.blank', [
            'titulo' => 'Correos Electrónicos',
            'icono' => 'bi-envelope',
            'seccion' => 'Correos',
            'descripcion' => 'Administración y configuración de cuentas de correo entrante (POP/IMAP) y saliente (SMTP).',
        ]);
    }

    /**
     * Muestra la vista en blanco de la lista de direcciones prohibidas.
     * Ruta: /admin/banlist
     */
    public function listaProhibidos(): View
    {
        return view('admin.blank', [
            'titulo' => 'Lista de Prohibidos',
            'icono' => 'bi-slash-circle',
            'seccion' => 'Correos',
            'descripcion' => 'Filtro y bloqueo de direcciones de correo o remitentes no permitidos.',
        ]);
    }

    /**
     * Muestra la vista en blanco de diagnóstico y prueba de correos.
     * Ruta: /admin/emailtest
     */
    public function diagnostico(): View
    {
        return view('admin.blank', [
            'titulo' => 'Diagnóstico de Correo',
            'icono' => 'bi-activity',
            'seccion' => 'Correos',
            'descripcion' => 'Herramienta de diagnóstico para verificar el envío y recepción de correos.',
        ]);
    }
}

