<?php

namespace App\Http\Controllers\Agent;

use App\DataTransferObjects\Ticket\TicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Models\Staff;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Repositories\DepartmentRepository;
use App\Repositories\TicketRepository;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService,
        protected TicketRepository $ticketRepository,
        protected DepartmentRepository $departmentRepository
    ) {}

    /**
     * Muestra la lista de tickets.
     */
    public function index(Request $request): View
    {
        $filtro = $request->input('filter');
        $tickets = $this->ticketRepository->obtenerPaginados($filtro, 20);

        $idPersonal = auth('staff')->id() ?? 1;
        $estadisticas = $this->ticketRepository->obtenerEstadisticas($idPersonal);

        return view('agent.tickets.index', compact('tickets', 'estadisticas'));
    }

    /**
     * Muestra el formulario para crear un nuevo ticket.
     */
    public function create(): View
    {
        $datos = $this->ticketService->obtenerDatosParaFormularioCreacion();

        return view('agent.tickets.create', $datos);
    }

    /**
     * Almacena un ticket recién creado en la base de datos siguiendo la arquitectura:
     * Form Request -> Controller -> DTO -> Service -> Repository.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $empresaId = auth('staff')->user()->empresa_id ?? 1;
        $staffId = auth('staff')->id() ?? 1;

        $ticketData = TicketData::fromRequest($request, $empresaId);

        $ticket = $this->ticketService->crearTicket(
            $ticketData,
            $request->input('body'),
            $staffId
        );

        return redirect()
            ->route('agent.tickets.show', $ticket->id)
            ->with('success', 'Ticket creado correctamente.');
    }

    /**
     * Muestra la vista detallada de un ticket específico.
     */
    public function show($id): View
    {
        $ticket = $this->ticketRepository->buscarConDetalles((int) $id);

        if (! $ticket) {
            abort(404, 'Ticket no encontrado');
        }

        $departamentos = $this->departmentRepository->obtenerTodos();
        $miembrosStaff = Staff::where('is_active', 1)->orderBy('firstname')->get();
        $estados = TicketStatus::orderBy('id')->get();

        return view('agent.tickets.show', compact('ticket', 'departamentos', 'miembrosStaff', 'estados'));
    }

    /**
     * Guarda una respuesta en el hilo del ticket.
     */
    public function responder(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validado = $request->validate([
            'response_body' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240', // Max 10MB
        ]);

        $this->ticketService->addReply($ticket, $validado['response_body'], auth('staff')->id() ?? 1, $request->file('attachments') ?? []);

        return redirect()->route('agent.tickets.show', $ticket->id)->with('success', 'Respuesta enviada correctamente.');
    }

    /**
     * Asigna un ticket a un agente.
     */
    public function asignar(Request $request, $id)
    {
        $request->validate(['staff_id' => 'required|exists:staff,id']);

        $ticket = Ticket::findOrFail($id);
        $ticket->staff_id = $request->staff_id;
        // Optionally save internal note
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket asignado correctamente.');
    }

    /**
     * Transfiere el ticket a otro departamento.
     */
    public function transferir(Request $request, $id)
    {
        $request->validate(['dept_id' => 'required|exists:departments,id']);

        $ticket = Ticket::findOrFail($id);
        $ticket->dept_id = $request->dept_id;
        // Optionally save internal note
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket transferido exitosamente.');
    }

    public function estado(Request $request, $id)
    {
        $request->validate(['status_id' => 'required|exists:ticket_status,id']);

        $ticket = Ticket::findOrFail($id);

        $this->ticketService->updateStatus($ticket, $request->status_id, auth('staff')->id() ?? 1);

        if ($request->status_id == 3) {
            return redirect()->back()
                ->with('success', 'Ticket cerrado. Recuerda llenar la hoja de reporte desde la sección "Por facturar".');
        }

        return redirect()->back()->with('success', 'Estado del ticket actualizado.');
    }

    /**
     * Guarda la firma del cliente dibujada en pantalla para cerrar el ticket.
     */
    public function requestSignature(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->has('firma_base64') && ! empty($request->firma_base64)) {
            $this->ticketService->saveSignatureFromBase64($ticket, $request->firma_base64);
            $this->ticketService->updateStatus($ticket, $request->input('status_id', 3), auth('staff')->id() ?? 1);

            return redirect()->back()
                ->with('success', 'Firma guardada y ticket cerrado correctamente. Recuerda llenar la hoja de reporte desde la sección "Por facturar".');
        }

        return redirect()->back()->with('error', 'No se recibió ninguna firma.');
    }

    /**
     * Actualiza el cuerpo de un mensaje del hilo.
     */
    public function actualizarHilo(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);

        $entrada = ThreadEntry::findOrFail($id);
        $entrada->body = $request->body;
        $entrada->updated = now();
        $entrada->save();

        return redirect()->back()->with('success', 'Mensaje actualizado correctamente.');
    }

    /**
     * Elimina un mensaje del hilo.
     */
    public function eliminarHilo($id)
    {
        $entrada = ThreadEntry::findOrFail($id);
        $entrada->delete();

        return redirect()->back()->with('success', 'Mensaje eliminado del hilo.');
    }

    /**
     * Elimina un ticket por completo.
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);

        // El hilo (Thread) y mensajes (ThreadEntry) podrían necesitar eliminarse
        // si no hay cascada en la base de datos, pero si asume cascada o se eliminan
        // los hijos desde el modelo.
        $ticket->delete();

        return redirect()->route('agent.tickets.index')->with('success', 'Ticket eliminado permanentemente.');
    }
}
