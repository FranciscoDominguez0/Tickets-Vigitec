<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\ActualizarConfiguracionEmpresaRequest;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    /**
     * Muestra la vista de configuración del perfil de la empresa (Páginas, logos y fondos).
     */
    public function perfilEmpresa(Request $solicitud): View
    {
        $nombreEmpresa = (string) AppSetting::obtener('company.name', '');
        $sitioWeb = (string) AppSetting::obtener('company.website', '');
        $telefono = (string) AppSetting::obtener('company.phone', '');
        $direccion = (string) AppSetting::obtener('company.address', '');

        $modoLogo = (string) AppSetting::obtener('company.logo_mode', 'default');
        $logoPersonalizado = (string) AppSetting::obtener('company.logo', '');

        $modoFondo = (string) AppSetting::obtener('login.background_mode', 'default');
        $fondoPersonalizado = (string) AppSetting::obtener('login.background', '');

        $pestañaActiva = $solicitud->query('tab', 'basic');
        if (! in_array($pestañaActiva, ['basic', 'logos', 'login'], true)) {
            $pestañaActiva = 'basic';
        }

        $objetivo = 'pages';

        return view('agent.settings.pages', compact(
            'nombreEmpresa',
            'sitioWeb',
            'telefono',
            'direccion',
            'modoLogo',
            'logoPersonalizado',
            'modoFondo',
            'fondoPersonalizado',
            'pestañaActiva',
            'objetivo'
        ));
    }

    /**
     * Guarda la configuración del perfil de la empresa.
     */
    public function guardarPerfilEmpresa(ActualizarConfiguracionEmpresaRequest $solicitud): RedirectResponse
    {
        // 1. Información básica
        if ($solicitud->filled('company_name')) {
            AppSetting::establecer('company.name', trim((string) $solicitud->input('company_name')));
        }
        if ($solicitud->has('company_website')) {
            AppSetting::establecer('company.website', trim((string) $solicitud->input('company_website')));
        }
        if ($solicitud->has('company_phone')) {
            AppSetting::establecer('company.phone', trim((string) $solicitud->input('company_phone')));
        }
        if ($solicitud->has('company_address')) {
            AppSetting::establecer('company.address', trim((string) $solicitud->input('company_address')));
        }

        // 2. Modo y subida de logotipo
        $modoLogo = $solicitud->input('company_logo_mode', 'default');
        AppSetting::establecer('company.logo_mode', $modoLogo);

        if ($modoLogo === 'default') {
            $logoPrevio = (string) AppSetting::obtener('company.logo', '');
            if ($logoPrevio !== '' && Storage::disk('public')->exists($logoPrevio)) {
                Storage::disk('public')->delete($logoPrevio);
            }
            AppSetting::establecer('company.logo', '');
        }

        if ($solicitud->hasFile('company_logo')) {
            $logoPrevio = (string) AppSetting::obtener('company.logo', '');
            if ($logoPrevio !== '' && Storage::disk('public')->exists($logoPrevio)) {
                Storage::disk('public')->delete($logoPrevio);
            }

            $rutaLogo = $solicitud->file('company_logo')->store('logos', 'public');
            AppSetting::establecer('company.logo', $rutaLogo);
        }

        // 3. Modo y subida de fondo para inicio de sesión
        $modoFondo = $solicitud->input('login_bg_mode', 'default');
        AppSetting::establecer('login.background_mode', $modoFondo);

        if ($modoFondo === 'default') {
            $fondoPrevio = (string) AppSetting::obtener('login.background', '');
            if ($fondoPrevio !== '' && Storage::disk('public')->exists($fondoPrevio)) {
                Storage::disk('public')->delete($fondoPrevio);
            }
            AppSetting::establecer('login.background', '');
        }

        if ($solicitud->hasFile('login_bg')) {
            $fondoPrevio = (string) AppSetting::obtener('login.background', '');
            if ($fondoPrevio !== '' && Storage::disk('public')->exists($fondoPrevio)) {
                Storage::disk('public')->delete($fondoPrevio);
            }

            $rutaFondo = $solicitud->file('login_bg')->store('backgrounds', 'public');
            AppSetting::establecer('login.background', $rutaFondo);
        }

        $pestañaActiva = $solicitud->input('active_tab', 'basic');

        return redirect()
            ->route('admin.settings.pages', ['tab' => $pestañaActiva])
            ->with('exito', 'Configuración de la empresa actualizada correctamente.');
    }

    /**
     * Muestra la vista en blanco de facturación.
     */
    public function facturacion(): View
    {
        return view('admin.blank', [
            'titulo' => 'Facturación',
            'icono' => 'bi-receipt',
            'seccion' => 'Configuración',
            'descripcion' => 'Configuración de planes, facturación y suscripción del sistema.',
        ]);
    }

    /**
     * Muestra la vista en blanco de solicitudes.
     */
    public function solicitudes(): View
    {
        return view('admin.blank', [
            'titulo' => 'Solicitudes',
            'icono' => 'bi-ticket-perforated',
            'seccion' => 'Configuración',
            'descripcion' => 'Ajustes globales para la creación, ciclo de vida y resolución de solicitudes de soporte.',
        ]);
    }

    /**
     * Muestra la vista en blanco de tareas.
     */
    public function tareas(): View
    {
        return view('admin.blank', [
            'titulo' => 'Tareas',
            'icono' => 'bi-check2-square',
            'seccion' => 'Configuración',
            'descripcion' => 'Gestión de configuraciones para tareas y asignaciones.',
        ]);
    }

    /**
     * Muestra la vista en blanco de configuración de agentes.
     */
    public function agentes(): View
    {
        return view('admin.blank', [
            'titulo' => 'Configuración de Agentes',
            'icono' => 'bi-person-badge',
            'seccion' => 'Configuración',
            'descripcion' => 'Políticas y parámetros generales aplicados a los agentes del sistema.',
        ]);
    }

    /**
     * Muestra la vista en blanco de configuración de usuarios.
     */
    public function usuarios(): View
    {
        return view('admin.blank', [
            'titulo' => 'Configuración de Usuarios',
            'icono' => 'bi-people',
            'seccion' => 'Configuración',
            'descripcion' => 'Parámetros del portal de clientes, registro y acceso de usuarios.',
        ]);
    }
}

