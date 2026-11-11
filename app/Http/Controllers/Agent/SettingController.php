<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\ActualizarConfiguracionEmpresaRequest;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Muestra las configuraciones del sistema según la pestaña solicitada.
     */
    public function mostrar(Request $solicitud): View
    {
        $objetivo = $solicitud->query('t', 'pages');

        if ($objetivo === 'pages') {
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

        // Para otras pestañas del panel de administración (billing, tickets, tasks, etc.)
        return view('agent.settings.pages', [
            'nombreEmpresa' => (string) AppSetting::obtener('company.name', ''),
            'sitioWeb' => (string) AppSetting::obtener('company.website', ''),
            'telefono' => (string) AppSetting::obtener('company.phone', ''),
            'direccion' => (string) AppSetting::obtener('company.address', ''),
            'modoLogo' => (string) AppSetting::obtener('company.logo_mode', 'default'),
            'logoPersonalizado' => (string) AppSetting::obtener('company.logo', ''),
            'modoFondo' => (string) AppSetting::obtener('login.background_mode', 'default'),
            'fondoPersonalizado' => (string) AppSetting::obtener('login.background', ''),
            'pestañaActiva' => 'basic',
            'objetivo' => $objetivo,
        ]);
    }

    /**
     * Guarda la información de la empresa, logos y fondos de login.
     */
    public function guardarPaginas(ActualizarConfiguracionEmpresaRequest $solicitud): RedirectResponse
    {
        // 1. Información básica
        if ($solicitud->filled('company_name')) {
            AppSetting::establecer('company.name', trim($solicitud->input('company_name')));
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

            $rutaLogo = $solicitud->file('company_logo')->store('settings', 'public');
            AppSetting::establecer('company.logo', $rutaLogo);
            AppSetting::establecer('company.logo_mode', 'custom');
        }

        // 3. Modo y subida de fondo de login
        $modoFondo = $solicitud->input('login_bg_mode', 'default');
        AppSetting::establecer('login.background_mode', $modoFondo);

        if ($modoFondo === 'default') {
            $fondoPrevio = (string) AppSetting::obtener('login.background', '');
            if ($fondoPrevio !== '' && Storage::disk('public')->exists($fondoPrevio)) {
                Storage::disk('public')->delete($fondoPrevio);
            }
            AppSetting::establecer('login.background', '');
        }

        if ($solicitud->hasFile('login_background')) {
            $fondoPrevio = (string) AppSetting::obtener('login.background', '');
            if ($fondoPrevio !== '' && Storage::disk('public')->exists($fondoPrevio)) {
                Storage::disk('public')->delete($fondoPrevio);
            }

            $rutaFondo = $solicitud->file('login_background')->store('settings', 'public');
            AppSetting::establecer('login.background', $rutaFondo);
            AppSetting::establecer('login.background_mode', 'custom');
        }

        $pestañaRedireccion = $solicitud->input('active_tab', 'basic');

        return redirect()
            ->route('agent.settings.pages', ['t' => 'pages', 'tab' => $pestañaRedireccion])
            ->with('exito', 'Cambios guardados correctamente.');
    }
}

