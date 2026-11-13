<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketDeletionRequest;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketBorradoController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    /**
     * Muestra el listado de tickets borrados o en papelera con opciones de auditoría y gestión.
     * Ruta: /admin/deleted-tickets
     */
    public function listar(Request $solicitud): View
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $busqueda = trim((string) $solicitud->input('q', ''));
        $filtroEstado = (string) $solicitud->input('estado', 'todos');

        $consulta = TicketDeletionRequest::with(['solicitante', 'resueltoPor'])
            ->where('empresa_id', $empresaId);

        if (! empty($busqueda)) {
            $consulta->where(function ($sub) use ($busqueda) {
                $sub->where('ticket_number', 'like', "%{$busqueda}%")
                    ->orWhere('ticket_subject', 'like', "%{$busqueda}%")
                    ->orWhere('reason', 'like', "%{$busqueda}%");
            });
        }

        if ($filtroEstado !== 'todos' && in_array($filtroEstado, ['pending', 'approved', 'rejected'], true)) {
            $consulta->where('status', $filtroEstado);
        }

        $registros = $consulta->orderByDesc('created_at')->paginate(15)->withQueryString();

        $estadisticas = [
            'total' => TicketDeletionRequest::where('empresa_id', $empresaId)->count(),
            'aprobados' => TicketDeletionRequest::where('empresa_id', $empresaId)->where('status', 'approved')->count(),
            'restaurados' => TicketDeletionRequest::where('empresa_id', $empresaId)->where('status', 'rejected')->count(),
            'pendientes' => TicketDeletionRequest::where('empresa_id', $empresaId)->where('status', 'pending')->count(),
        ];

        return view('admin.tickets.deleted', compact('registros', 'estadisticas', 'busqueda', 'filtroEstado'));
    }

    /**
     * Restaura un ticket eliminado a partir de la instantánea archivada.
     */
    public function restaurar(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $solicitud = TicketDeletionRequest::where('empresa_id', $empresaId)->findOrFail($id);

        $staffId = (int) (auth('staff')->id() ?? 1);
        $ticket = $this->ticketService->restaurarTicket($solicitud->id, $staffId);

        return redirect()->route('admin.deleted_tickets')
            ->with('success', "Ticket #{$ticket->ticket_number} restaurado con éxito y reincorporado al sistema.");
    }

    /**
     * Purga y elimina definitivamente el registro del historial.
     */
    public function purgar(int $id): RedirectResponse
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);
        $solicitud = TicketDeletionRequest::where('empresa_id', $empresaId)->findOrFail($id);

        $numeroTicket = $solicitud->ticket_number;
        $this->ticketService->purgarSolicitudBorrado($solicitud->id);

        return redirect()->route('admin.deleted_tickets')
            ->with('success', "Registro de auditoría del ticket #{$numeroTicket} purgado permanentemente.");
    }
}
