<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CrearTicketClienteRequest;
use App\Http\Requests\Client\ResponderTicketClienteRequest;
use App\Models\Department;
use App\Models\HelpTopic;
use App\Repositories\TicketRepository;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $servicioTicket,
        protected TicketRepository $repositorioTicket
    ) {}

    /**
     * Muestra el panel de control y listado de tickets del cliente autenticado.
     */
    public function listar(Request $peticion): View
    {
        $usuario = auth()->user();
        $empresaId = (int) ($usuario->empresa_id ?? 1);

        $filtro = (string) $peticion->query('filtro', 'abiertos');
        if (! in_array($filtro, ['abiertos', 'cerrados', 'todos'], true)) {
            $filtro = 'abiertos';
        }

        $busqueda = trim((string) $peticion->query('q', ''));

        $tickets = $this->repositorioTicket->obtenerPorUsuarioPaginados(
            usuarioId: $usuario->id,
            empresaId: $empresaId,
            filtro: $filtro,
            busqueda: $busqueda !== '' ? $busqueda : null,
            porPagina: 10
        )->withQueryString();

        $estadisticas = $this->repositorioTicket->obtenerEstadisticasUsuario(
            usuarioId: $usuario->id,
            empresaId: $empresaId
        );

        return view('client.tickets.index', [
            'tickets' => $tickets,
            'estadisticas' => $estadisticas,
            'filtro' => $filtro,
            'busqueda' => $busqueda,
        ]);
    }

    /**
     * Muestra el formulario para que el cliente cree un nuevo ticket.
     */
    public function crear(): View
    {
        $usuario = auth()->user();
        $empresaId = (int) ($usuario->empresa_id ?? 1);

        $departamentos = Department::where('empresa_id', $empresaId)
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        $temas = HelpTopic::where('empresa_id', $empresaId)
            ->where('is_active', 1)
            ->where('is_public', 1)
            ->orderBy('name')
            ->get();

        return view('client.tickets.create', [
            'departamentos' => $departamentos,
            'temas' => $temas,
            'usuario' => $usuario,
        ]);
    }

    /**
     * Procesa y almacena un nuevo ticket creado por el cliente.
     */
    public function guardar(CrearTicketClienteRequest $solicitud): RedirectResponse
    {
        $usuario = auth()->user();
        $empresaId = (int) ($usuario->empresa_id ?? 1);

        $departamentoId = (int) $solicitud->validated('dept_id');
        $temaId = $solicitud->filled('topic_id') ? (int) $solicitud->validated('topic_id') : null;

        // Si seleccionó tema y este tiene un departamento asociado, priorizarlo
        if ($temaId) {
            $tema = HelpTopic::where('empresa_id', $empresaId)->find($temaId);
            if ($tema && $tema->dept_id) {
                $departamentoId = (int) $tema->dept_id;
            }
        }

        $adjuntos = $solicitud->hasFile('attachments') ? $solicitud->file('attachments') : [];

        $ticket = $this->servicioTicket->crearTicketCliente(
            usuarioId: $usuario->id,
            empresaId: $empresaId,
            departamentoId: $departamentoId,
            asunto: (string) $solicitud->validated('subject'),
            cuerpo: (string) $solicitud->validated('body'),
            temaId: $temaId,
            anydesk: $solicitud->validated('anydesk'),
            adjuntos: is_array($adjuntos) ? $adjuntos : [$adjuntos]
        );

        return redirect()->route('client.tickets.show', $ticket->id)
            ->with('success', 'Tu ticket #' . $ticket->ticket_number . ' ha sido creado exitosamente.');
    }

    /**
     * Muestra el detalle del ticket y su hilo de conversación.
     */
    public function mostrar(int $id): View
    {
        $usuario = auth()->user();
        $empresaId = (int) ($usuario->empresa_id ?? 1);

        $ticket = $this->repositorioTicket->buscarParaUsuario(
            id: $id,
            usuarioId: $usuario->id,
            empresaId: $empresaId
        );

        if (! $ticket) {
            abort(404, 'Ticket no encontrado.');
        }

        return view('client.tickets.show', [
            'ticket' => $ticket,
            'usuario' => $usuario,
        ]);
    }

    /**
     * Procesa una nueva respuesta enviada por el cliente.
     */
    public function responder(ResponderTicketClienteRequest $solicitud, int $id): RedirectResponse
    {
        $usuario = auth()->user();
        $empresaId = (int) ($usuario->empresa_id ?? 1);

        $ticket = $this->repositorioTicket->buscarParaUsuario(
            id: $id,
            usuarioId: $usuario->id,
            empresaId: $empresaId
        );

        if (! $ticket) {
            abort(404, 'Ticket no encontrado.');
        }

        $adjuntos = $solicitud->hasFile('attachments') ? $solicitud->file('attachments') : [];

        $this->servicioTicket->responderCliente(
            ticket: $ticket,
            usuarioId: $usuario->id,
            cuerpo: (string) $solicitud->validated('body'),
            adjuntos: is_array($adjuntos) ? $adjuntos : [$adjuntos]
        );

        return redirect()->route('client.tickets.show', $ticket->id)
            ->with('success', 'Respuesta enviada correctamente.');
    }
}
