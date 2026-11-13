<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ActualizarDestinatariosNotificacionRequest;
use App\Http\Requests\Admin\EnviarNotificacionRequest;
use App\Http\Requests\Admin\ProcesarNotificacionesMasivoRequest;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\Staff;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    /**
     * Muestra el panel de notificaciones con métricas, historial y configuración de destinatarios.
     * Ruta: /admin/notifications
     */
    public function listar(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $busqueda = trim((string) $solicitud->input('q', ''));
        $filtroTipo = (string) $solicitud->input('tipo', 'todos');
        $filtroEstado = (string) $solicitud->input('estado', 'todos');
        $filtroAudiencia = (string) $solicitud->input('audiencia', 'personal');
        $pestañaActiva = (string) $solicitud->input('tab', 'historial');

        // Consulta de notificaciones internas del personal
        $consultaPersonal = Notification::with(['staff', 'ticket'])
            ->where('empresa_id', $empresaId);

        if (! empty($busqueda)) {
            $consultaPersonal->where(function ($query) use ($busqueda) {
                $query->where('message', 'like', "%{$busqueda}%")
                    ->orWhere('type', 'like', "%{$busqueda}%")
                    ->orWhereHas('staff', function ($sub) use ($busqueda) {
                        $sub->where('firstname', 'like', "%{$busqueda}%")
                            ->orWhere('lastname', 'like', "%{$busqueda}%")
                            ->orWhere('email', 'like', "%{$busqueda}%");
                    });
            });
        }

        if ($filtroTipo !== 'todos') {
            $consultaPersonal->where('type', $filtroTipo);
        }

        if ($filtroEstado === 'no_leidas') {
            $consultaPersonal->where('is_read', 0);
        } elseif ($filtroEstado === 'leidas') {
            $consultaPersonal->where('is_read', 1);
        }

        $notificacionesPersonal = $consultaPersonal->orderBy('created_at', 'desc')->paginate(20, ['*'], 'pag_personal');

        // Consulta de notificaciones enviadas a clientes
        $consultaClientes = UserNotification::with(['user', 'ticket'])
            ->where('empresa_id', $empresaId);

        if (! empty($busqueda)) {
            $consultaClientes->where(function ($query) use ($busqueda) {
                $query->where('message', 'like', "%{$busqueda}%")
                    ->orWhere('type', 'like', "%{$busqueda}%")
                    ->orWhereHas('user', function ($sub) use ($busqueda) {
                        $sub->where('name', 'like', "%{$busqueda}%")
                            ->orWhere('email', 'like', "%{$busqueda}%");
                    });
            });
        }

        if ($filtroTipo !== 'todos') {
            $consultaClientes->where('type', $filtroTipo);
        }

        if ($filtroEstado === 'no_leidas') {
            $consultaClientes->where('is_read', 0);
        } elseif ($filtroEstado === 'leidas') {
            $consultaClientes->where('is_read', 1);
        }

        $notificacionesClientes = $consultaClientes->orderBy('created_at', 'desc')->paginate(20, ['*'], 'pag_clientes');

        // Métricas globales
        $totalPersonal = Notification::where('empresa_id', $empresaId)->count();
        $noLeidasPersonal = Notification::where('empresa_id', $empresaId)->where('is_read', 0)->count();
        $totalClientes = UserNotification::where('empresa_id', $empresaId)->count();
        $noLeidasClientes = UserNotification::where('empresa_id', $empresaId)->where('is_read', 0)->count();

        $estadisticas = [
            'total' => $totalPersonal + $totalClientes,
            'no_leidas' => $noLeidasPersonal + $noLeidasClientes,
            'alertas_personal' => $totalPersonal,
            'alertas_clientes' => $totalClientes,
        ];

        // Personal disponible y destinatarios de alertas globales
        $agentes = Staff::where('empresa_id', $empresaId)
            ->where('is_active', 1)
            ->orderBy('firstname', 'asc')
            ->get();

        $destinatariosGlobales = NotificationRecipient::where('empresa_id', $empresaId)
            ->pluck('staff_id')
            ->toArray();

        // Clientes recientes para el selector del modal
        $clientes = User::where('empresa_id', $empresaId)
            ->orderBy('id', 'desc')
            ->take(50)
            ->get();

        return view('admin.notifications.index', compact(
            'notificacionesPersonal',
            'notificacionesClientes',
            'estadisticas',
            'agentes',
            'clientes',
            'destinatariosGlobales',
            'busqueda',
            'filtroTipo',
            'filtroEstado',
            'filtroAudiencia',
            'pestañaActiva'
        ));
    }

    /**
     * Envía una nueva notificación manual a agentes o clientes.
     */
    public function enviar(EnviarNotificacionRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $destinatarioTipo = (string) $solicitud->input('destinatario_tipo');
        $mensaje = trim((string) $solicitud->input('message'));
        $tipo = (string) $solicitud->input('type', 'general');
        $ticketId = $solicitud->filled('related_id') ? (int) $solicitud->input('related_id') : null;

        $totalEnviadas = 0;

        if ($destinatarioTipo === 'todos_agentes') {
            $agentes = Staff::where('empresa_id', $empresaId)->where('is_active', 1)->get();
            foreach ($agentes as $agente) {
                Notification::create([
                    'empresa_id' => $empresaId,
                    'staff_id' => $agente->id,
                    'message' => $mensaje,
                    'type' => $tipo,
                    'related_id' => $ticketId,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
                $totalEnviadas++;
            }
        } elseif ($destinatarioTipo === 'agente_especifico') {
            $staffId = (int) $solicitud->input('staff_id');
            Notification::create([
                'empresa_id' => $empresaId,
                'staff_id' => $staffId,
                'message' => $mensaje,
                'type' => $tipo,
                'related_id' => $ticketId,
                'is_read' => 0,
                'created_at' => now(),
            ]);
            $totalEnviadas = 1;
        } elseif ($destinatarioTipo === 'todos_clientes') {
            $usuarios = User::where('empresa_id', $empresaId)->get();
            foreach ($usuarios as $usuario) {
                UserNotification::create([
                    'empresa_id' => $empresaId,
                    'user_id' => $usuario->id,
                    'message' => $mensaje,
                    'type' => $tipo,
                    'ticket_id' => $ticketId,
                    'is_read' => 0,
                    'created_at' => now(),
                ]);
                $totalEnviadas++;
            }
        } elseif ($destinatarioTipo === 'cliente_especifico') {
            $userId = (int) $solicitud->input('user_id');
            UserNotification::create([
                'empresa_id' => $empresaId,
                'user_id' => $userId,
                'message' => $mensaje,
                'type' => $tipo,
                'ticket_id' => $ticketId,
                'is_read' => 0,
                'created_at' => now(),
            ]);
            $totalEnviadas = 1;
        }

        return redirect()->route('admin.notifications', ['audiencia' => str_contains($destinatarioTipo, 'cliente') ? 'clientes' : 'personal'])
            ->with('success', "Se envió la notificación exitosamente a {$totalEnviadas} destinatario(s).");
    }

    /**
     * Marca una notificación específica como leída.
     */
    public function marcarLeida(Request $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $audiencia = (string) $solicitud->input('audiencia', 'personal');

        if ($audiencia === 'clientes') {
            $notificacion = UserNotification::where('empresa_id', $empresaId)->findOrFail($id);
            $notificacion->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);
        } else {
            $notificacion = Notification::where('empresa_id', $empresaId)->findOrFail($id);
            $notificacion->update(['is_read' => 1]);
        }

        return redirect()->route('admin.notifications', ['audiencia' => $audiencia])
            ->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Marca todas las notificaciones pendientes de la empresa como leídas.
     */
    public function marcarTodasLeidas(Request $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $audiencia = (string) $solicitud->input('audiencia', 'personal');

        if ($audiencia === 'clientes') {
            UserNotification::where('empresa_id', $empresaId)
                ->where('is_read', 0)
                ->update([
                    'is_read' => 1,
                    'read_at' => now(),
                ]);
        } else {
            Notification::where('empresa_id', $empresaId)
                ->where('is_read', 0)
                ->update(['is_read' => 1]);
        }

        return redirect()->route('admin.notifications', ['audiencia' => $audiencia])
            ->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }

    /**
     * Elimina una notificación individual del historial.
     */
    public function eliminar(Request $solicitud, int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $audiencia = (string) $solicitud->input('audiencia', 'personal');

        if ($audiencia === 'clientes') {
            UserNotification::where('empresa_id', $empresaId)->where('id', $id)->delete();
        } else {
            Notification::where('empresa_id', $empresaId)->where('id', $id)->delete();
        }

        return redirect()->route('admin.notifications', ['audiencia' => $audiencia])
            ->with('success', 'Notificación eliminada correctamente.');
    }

    /**
     * Procesa acciones masivas sobre notificaciones seleccionadas.
     */
    public function procesarMasivo(ProcesarNotificacionesMasivoRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $solicitud->input('ids', [])))));
        $accion = (string) $solicitud->input('accion');
        $audiencia = (string) $solicitud->input('audiencia', 'personal');

        if (empty($ids)) {
            return redirect()->route('admin.notifications', ['audiencia' => $audiencia])
                ->with('error', 'Debes seleccionar al menos una notificación.');
        }

        if ($audiencia === 'clientes') {
            if ($accion === 'marcar_leido') {
                UserNotification::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_read' => 1, 'read_at' => now()]);

                return redirect()->route('admin.notifications', ['audiencia' => $audiencia])->with('success', 'Notificaciones marcadas como leídas.');
            }

            if ($accion === 'marcar_no_leido') {
                UserNotification::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_read' => 0, 'read_at' => null]);

                return redirect()->route('admin.notifications', ['audiencia' => $audiencia])->with('success', 'Notificaciones marcadas como no leídas.');
            }

            if ($accion === 'eliminar') {
                UserNotification::where('empresa_id', $empresaId)->whereIn('id', $ids)->delete();

                return redirect()->route('admin.notifications', ['audiencia' => $audiencia])->with('success', 'Notificaciones eliminadas correctamente.');
            }
        } else {
            if ($accion === 'marcar_leido') {
                Notification::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_read' => 1]);

                return redirect()->route('admin.notifications', ['audiencia' => $audiencia])->with('success', 'Notificaciones marcadas como leídas.');
            }

            if ($accion === 'marcar_no_leido') {
                Notification::where('empresa_id', $empresaId)->whereIn('id', $ids)->update(['is_read' => 0]);

                return redirect()->route('admin.notifications', ['audiencia' => $audiencia])->with('success', 'Notificaciones marcadas como no leídas.');
            }

            if ($accion === 'eliminar') {
                Notification::where('empresa_id', $empresaId)->whereIn('id', $ids)->delete();

                return redirect()->route('admin.notifications', ['audiencia' => $audiencia])->with('success', 'Notificaciones eliminadas correctamente.');
            }
        }

        return redirect()->route('admin.notifications', ['audiencia' => $audiencia])->with('error', 'Acción masiva no reconocida.');
    }

    /**
     * Guarda la configuración de agentes asignados a recibir alertas globales del sistema.
     */
    public function guardarDestinatarios(ActualizarDestinatariosNotificacionRequest $solicitud): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $staffIds = array_values(array_unique(array_filter(array_map('intval', (array) $solicitud->input('staff_ids', [])))));

        // Eliminar destinatarios actuales de la empresa
        NotificationRecipient::where('empresa_id', $empresaId)->delete();

        // Insertar los nuevos destinatarios seleccionados
        foreach ($staffIds as $staffId) {
            NotificationRecipient::create([
                'empresa_id' => $empresaId,
                'staff_id' => $staffId,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('admin.notifications', ['tab' => 'destinatarios'])
            ->with('success', 'Destinatarios de alertas del sistema actualizados correctamente.');
    }
}
