<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActualizarConfiguracionEmpresaRequest;
use App\Http\Requests\Admin\ActualizarConfiguracionSolicitudesRequest;
use App\Models\AppSetting;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Models\Priority;
use App\Models\Sequence;
use App\Models\TicketStatus;
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

        return view('admin.settings.pages', compact(
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
     * Muestra la pantalla de configuración de Solicitudes y Numeración de Tickets.
     */
    public function solicitudes(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $formatoNumero = (string) AppSetting::obtener('tickets.ticket_number_format', '######', $empresaId);
        $secuenciaId = (string) AppSetting::obtener('tickets.ticket_sequence_id', '0', $empresaId);
        $estadoPorDefecto = (string) AppSetting::obtener('tickets.default_ticket_status_id', '1', $empresaId);
        $prioridadPorDefecto = (string) AppSetting::obtener('tickets.default_priority_id', '1', $empresaId);
        $temaPorDefecto = (string) AppSetting::obtener('tickets.default_help_topic', '0', $empresaId);
        $maxTicketsAbiertos = (string) AppSetting::obtener('tickets.max_open_tickets', '0', $empresaId);
        $bloquearPorFirma = (bool) AppSetting::obtener('tickets.block_new_if_signature_pending', false, $empresaId);
        $tamanoMaxArchivoMb = (string) AppSetting::obtener('tickets.ticket_max_file_mb', '10', $empresaId);
        $maxSubidas = (string) AppSetting::obtener('tickets.ticket_max_uploads', '5', $empresaId);

        $estados = TicketStatus::orderBy('order_by')->get();
        $prioridades = Priority::orderBy('level')->get();
        $temas = HelpTopic::where('empresa_id', $empresaId)->where('is_active', 1)->orderBy('name')->get();
        $secuencias = Sequence::where('empresa_id', $empresaId)->orderBy('id')->get();
        $departamentos = Department::where('empresa_id', $empresaId)->where('is_active', 1)->orderBy('name')->get();

        // Generar un número de ejemplo basado en el formato o secuencia
        $ejemploNumero = '610781';
        if ($secuenciaId !== '0') {
            $secuenciaSeleccionada = $secuencias->firstWhere('id', (int) $secuenciaId);
            if ($secuenciaSeleccionada) {
                $ejemploNumero = $secuenciaSeleccionada->obtenerNumeroFormateado();
            }
        } elseif (preg_match('/^#+$/', $formatoNumero)) {
            $ejemploNumero = '';
            for ($i = 0; $i < strlen($formatoNumero); $i++) {
                $ejemploNumero .= (string) random_int(0, 9);
            }
        }

        return view('admin.settings.tickets', compact(
            'formatoNumero',
            'secuenciaId',
            'estadoPorDefecto',
            'prioridadPorDefecto',
            'temaPorDefecto',
            'maxTicketsAbiertos',
            'bloquearPorFirma',
            'tamanoMaxArchivoMb',
            'maxSubidas',
            'estados',
            'prioridades',
            'temas',
            'secuencias',
            'departamentos',
            'ejemploNumero'
        ));
    }

    /**
     * Guarda la configuración de Solicitudes y Numeración de Tickets.
     */
    public function guardarSolicitudes(ActualizarConfiguracionSolicitudesRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        AppSetting::establecer('tickets.ticket_number_format', trim((string) $solicitud->validated('ticket_number_format')), $empresaId);
        AppSetting::establecer('tickets.ticket_sequence_id', (string) $solicitud->validated('ticket_sequence_id'), $empresaId);
        AppSetting::establecer('tickets.default_ticket_status_id', (string) $solicitud->validated('default_ticket_status_id'), $empresaId);
        AppSetting::establecer('tickets.default_priority_id', (string) $solicitud->validated('default_priority_id'), $empresaId);
        AppSetting::establecer('tickets.default_help_topic', (string) ($solicitud->validated('default_help_topic') ?? '0'), $empresaId);
        AppSetting::establecer('tickets.max_open_tickets', (string) ($solicitud->validated('max_open_tickets') ?? '0'), $empresaId);
        AppSetting::establecer('tickets.block_new_if_signature_pending', $solicitud->boolean('block_new_if_signature_pending') ? '1' : '0', $empresaId);
        AppSetting::establecer('tickets.ticket_max_file_mb', (string) $solicitud->validated('ticket_max_file_mb'), $empresaId);
        AppSetting::establecer('tickets.ticket_max_uploads', (string) $solicitud->validated('ticket_max_uploads'), $empresaId);

        return redirect()->route('admin.settings.tickets')
            ->with('success', 'Configuración de Solicitudes y Números de Tickets guardada exitosamente.');
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
