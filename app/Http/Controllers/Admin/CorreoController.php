<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActualizarCuentaCorreoRequest;
use App\Http\Requests\Admin\GuardarBanlistRequest;
use App\Http\Requests\Admin\GuardarCuentaCorreoRequest;
use App\Models\Banlist;
use App\Models\Department;
use App\Models\EmailAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class CorreoController extends Controller
{
    /**
     * Muestra el listado de cuentas de correo electrónico configuradas.
     * Ruta: /admin/email
     */
    public function listar(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $cuentas = EmailAccount::with('departamento')
            ->where('empresa_id', $empresaId)
            ->orderByDesc('is_default')
            ->orderBy('id', 'asc')
            ->get();

        $departamentos = Department::where('empresa_id', $empresaId)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.emails.index', compact('cuentas', 'departamentos'));
    }

    /**
     * Registra una nueva cuenta de correo electrónico.
     */
    public function guardar(GuardarCuentaCorreoRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $esPredeterminada = $solicitud->boolean('is_default');

        if ($esPredeterminada) {
            EmailAccount::where('empresa_id', $empresaId)->update(['is_default' => 0]);
        }

        EmailAccount::create([
            'empresa_id' => $empresaId,
            'email' => strtolower(trim((string) $solicitud->validated('email'))),
            'name' => trim((string) $solicitud->validated('name')),
            'priority' => (string) $solicitud->validated('priority'),
            'dept_id' => $solicitud->validated('dept_id'),
            'is_default' => $esPredeterminada ? 1 : 0,
            'smtp_host' => $solicitud->validated('smtp_host'),
            'smtp_port' => $solicitud->validated('smtp_port'),
            'smtp_secure' => $solicitud->validated('smtp_secure') ?: 'none',
            'smtp_user' => $solicitud->validated('smtp_user'),
            'smtp_pass' => $solicitud->validated('smtp_pass'),
        ]);

        return redirect()->route('admin.email')
            ->with('success', 'Cuenta de correo registrada exitosamente.');
    }

    /**
     * Actualiza una cuenta de correo electrónico existente.
     */
    public function actualizar(ActualizarCuentaCorreoRequest $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $cuenta = EmailAccount::where('empresa_id', $empresaId)->findOrFail($id);

        $esPredeterminada = $solicitud->boolean('is_default');

        if ($esPredeterminada && ! $cuenta->is_default) {
            EmailAccount::where('empresa_id', $empresaId)->update(['is_default' => 0]);
        }

        $datosActualizacion = [
            'email' => strtolower(trim((string) $solicitud->validated('email'))),
            'name' => trim((string) $solicitud->validated('name')),
            'priority' => (string) $solicitud->validated('priority'),
            'dept_id' => $solicitud->validated('dept_id'),
            'is_default' => $esPredeterminada ? 1 : 0,
            'smtp_host' => $solicitud->validated('smtp_host'),
            'smtp_port' => $solicitud->validated('smtp_port'),
            'smtp_secure' => $solicitud->validated('smtp_secure') ?: 'none',
            'smtp_user' => $solicitud->validated('smtp_user'),
        ];

        if ($solicitud->filled('smtp_pass')) {
            $datosActualizacion['smtp_pass'] = $solicitud->validated('smtp_pass');
        }

        $cuenta->update($datosActualizacion);

        return redirect()->route('admin.email')
            ->with('success', 'Cuenta de correo actualizada correctamente.');
    }

    /**
     * Establece una cuenta de correo como predeterminada del sistema.
     */
    public function marcarPredeterminada(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $cuenta = EmailAccount::where('empresa_id', $empresaId)->findOrFail($id);

        EmailAccount::where('empresa_id', $empresaId)->update(['is_default' => 0]);
        $cuenta->update(['is_default' => 1]);

        return redirect()->route('admin.email')
            ->with('success', 'La cuenta "'.$cuenta->email.'" ha sido establecida como predeterminada.');
    }

    /**
     * Elimina una cuenta de correo electrónico.
     */
    public function eliminar(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $cuenta = EmailAccount::where('empresa_id', $empresaId)->findOrFail($id);

        $cuenta->delete();

        return redirect()->route('admin.email')
            ->with('success', 'Cuenta de correo eliminada correctamente.');
    }

    /**
     * Muestra el listado de correos y dominios prohibidos (Banlist).
     * Ruta: /admin/banlist
     */
    public function listaProhibidos(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $prohibidos = Banlist::where('empresa_id', $empresaId)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.emails.banlist', compact('prohibidos'));
    }

    /**
     * Registra un nuevo correo o dominio en la lista de prohibidos.
     */
    public function guardarProhibido(GuardarBanlistRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $valor = trim((string) $solicitud->validated('value'));
        $tipo = $solicitud->validated('type');

        $correo = null;
        $dominio = null;

        if ($tipo === 'email') {
            $correo = strtolower($valor);
        } else {
            $dominio = strtolower(ltrim($valor, '@'));
        }

        Banlist::create([
            'empresa_id' => $empresaId,
            'email' => $correo,
            'domain' => $dominio,
            'notes' => $solicitud->validated('notes'),
            'is_active' => 1,
        ]);

        return redirect()->route('admin.banlist')
            ->with('success', 'Elemento agregado a la lista de prohibidos exitosamente.');
    }

    /**
     * Alterna el estado activo/inactivo de una regla de prohibición.
     */
    public function alternarEstadoProhibido(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $bloqueo = Banlist::where('empresa_id', $empresaId)->findOrFail($id);

        $bloqueo->update([
            'is_active' => ! $bloqueo->is_active,
        ]);

        $estadoTexto = $bloqueo->is_active ? 'activada' : 'desactivada';

        return redirect()->route('admin.banlist')
            ->with('success', 'La regla de prohibición ha sido '.$estadoTexto.'.');
    }

    /**
     * Elimina una regla de la lista de prohibidos.
     */
    public function eliminarProhibido(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $bloqueo = Banlist::where('empresa_id', $empresaId)->findOrFail($id);

        $bloqueo->delete();

        return redirect()->route('admin.banlist')
            ->with('success', 'Regla de prohibición eliminada correctamente.');
    }

    /**
     * Muestra la pantalla de diagnóstico y prueba de correos electrónicos.
     * Ruta: /admin/emailtest
     */
    public function diagnostico(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        $cuentas = EmailAccount::where('empresa_id', $empresaId)
            ->orderByDesc('is_default')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.emails.test', compact('cuentas'));
    }

    /**
     * Ejecuta una prueba de envío de correo SMTP.
     */
    public function enviarPrueba(Request $solicitud): RedirectResponse
    {
        $solicitud->validate([
            'from_id' => ['required', 'integer'],
            'to' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ], [
            'from_id.required' => 'Debes seleccionar una cuenta de correo emisora.',
            'to.required' => 'Debes indicar el correo de destino para la prueba.',
            'to.email' => 'El correo de destino no es válido.',
            'subject.required' => 'El asunto del mensaje de prueba es obligatorio.',
            'body.required' => 'El contenido del mensaje de prueba es obligatorio.',
        ]);

        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $cuenta = EmailAccount::where('empresa_id', $empresaId)->findOrFail((int) $solicitud->input('from_id'));

        $destinatario = (string) $solicitud->input('to');
        $asunto = (string) $solicitud->input('subject');
        $cuerpo = (string) $solicitud->input('body');

        try {
            Mail::raw($cuerpo, function ($mensaje) use ($cuenta, $destinatario, $asunto) {
                $mensaje->to($destinatario)
                    ->from($cuenta->email, $cuenta->name)
                    ->subject($asunto);
            });

            return redirect()->route('admin.emailtest')
                ->with('success', 'Correo de prueba enviado exitosamente a '.$destinatario.' usando la cuenta '.$cuenta->email.'.');
        } catch (Throwable $e) {
            return redirect()->route('admin.emailtest')
                ->with('error', 'Error al enviar el correo de prueba: '.$e->getMessage())
                ->withInput();
        }
    }
}
