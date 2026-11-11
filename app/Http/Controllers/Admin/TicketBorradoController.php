<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TicketBorradoController extends Controller
{
    /**
     * Muestra la vista en blanco de tickets borrados o en papelera.
     * Ruta: /admin/deleted-tickets
     */
    public function listar(): View
    {
        return view('admin.blank', [
            'titulo' => 'Tickets Borrados',
            'icono' => 'bi-trash-fill',
            'seccion' => 'Gestión',
            'descripcion' => 'Revisión y restauración o purga definitiva de solicitudes eliminadas del sistema.',
        ]);
    }
}

