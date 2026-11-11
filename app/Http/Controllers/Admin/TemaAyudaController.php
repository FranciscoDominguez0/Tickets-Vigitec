<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TemaAyudaController extends Controller
{
    /**
     * Muestra la vista en blanco de temas de ayuda (Help Topics).
     * Ruta: /admin/helptopics
     */
    public function listar(): View
    {
        return view('admin.blank', [
            'titulo' => 'Temas de Ayuda',
            'icono' => 'bi-list-check',
            'seccion' => 'Gestión',
            'descripcion' => 'Clasificación y definición de temas de consulta para enrutar las solicitudes de los usuarios.',
        ]);
    }
}

