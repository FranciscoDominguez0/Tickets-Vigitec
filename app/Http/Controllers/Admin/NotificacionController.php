<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    /**
     * Muestra la vista en blanco de notificaciones administrativas.
     * Ruta: /admin/notifications
     */
    public function listar(): View
    {
        return view('admin.blank', [
            'titulo' => 'Notificaciones',
            'icono' => 'bi-bell',
            'seccion' => 'Sistema',
            'descripcion' => 'Historial y configuración de avisos y alertas del sistema para administradores.',
        ]);
    }
}
