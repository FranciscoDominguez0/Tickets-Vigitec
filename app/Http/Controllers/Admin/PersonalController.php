<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PersonalController extends Controller
{
    /**
     * Muestra la vista en blanco de gestión de agentes/staff.
     * Ruta: /admin/staff
     */
    public function agentes(): View
    {
        return view('admin.blank', [
            'titulo' => 'Agentes',
            'icono' => 'bi-person-badge',
            'seccion' => 'Agentes',
            'descripcion' => 'Administración de cuentas de agentes, credenciales y asignación de departamentos.',
        ]);
    }

    /**
     * Muestra la vista en blanco de gestión de roles.
     * Ruta: /admin/roles
     */
    public function roles(): View
    {
        return view('admin.blank', [
            'titulo' => 'Roles y Permisos',
            'icono' => 'bi-shield-lock',
            'seccion' => 'Agentes',
            'descripcion' => 'Definición de roles y matrices de permisos de acceso para el personal.',
        ]);
    }

    /**
     * Muestra la vista en blanco de gestión de departamentos.
     * Ruta: /admin/departments
     */
    public function departamentos(): View
    {
        return view('admin.blank', [
            'titulo' => 'Departamentos',
            'icono' => 'bi-diagram-3',
            'seccion' => 'Agentes',
            'descripcion' => 'Estructura organizativa de departamentos y niveles de escalamiento del soporte.',
        ]);
    }
}
