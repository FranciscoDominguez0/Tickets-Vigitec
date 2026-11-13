<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PanelControlController extends Controller
{
    /**
     * Muestra la vista en blanco de registros (logs) del sistema.
     * Ruta: /admin/logs
     */
    public function registros(): View
    {
        return view('admin.blank', [
            'titulo' => 'Registros del Sistema (Logs)',
            'icono' => 'bi-terminal',
            'seccion' => 'Panel de Control',
            'descripcion' => 'Historial de eventos, errores y actividades registradas en el sistema.',
        ]);
    }

    /**
     * Muestra la vista en blanco de auditoría del sistema.
     * Ruta: /admin/audit
     */
    public function auditoria(): View
    {
        return view('admin.blank', [
            'titulo' => 'Auditoría',
            'icono' => 'bi-shield-check',
            'seccion' => 'Panel de Control',
            'descripcion' => 'Rastreo y auditoría de accesos y modificaciones efectuadas por usuarios y agentes.',
        ]);
    }
}
