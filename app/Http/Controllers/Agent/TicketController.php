<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Priority;
use App\Models\Staff;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    /**
     * Muestra la lista de tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'department', 'priority', 'status', 'staff', 'thread.entries'])
            ->orderBy('created', 'desc');

        if ($request->input('filter') === 'billing') {
            // Filtrar tickets cerrados que necesitan reporte pero que aún no lo tienen, etc.
            // Por ahora, como es "por facturar", listamos tickets que no tengan reporte
            $query->where('status_id', 3)->whereDoesntHave('report');
        }

        $tickets = $query->paginate(20);

        $idPersonal = auth('staff')->id() ?? 1;

        $estadisticas = [
            'open' => Ticket::where('status_id', 1)->count(), // Suponiendo 1 = Abierto
            'unassigned' => Ticket::whereNull('staff_id')->orWhere('staff_id', 0)->count(),
            'mine' => Ticket::where('staff_id', $idPersonal)->count(),
            'billing' => 0, // Ajustar según lógica de facturación
        ];

        return view('agent.tickets.index', compact('tickets', 'estadisticas'));
    }

    /**
     * Muestra el formulario para crear un nuevo ticket.
     */
    public function create()
    {
        $departamentos = Department::where('is_active', 1)->orderBy('name')->get();
        $prioridades = Priority::orderBy('id')->get();

        // Obtenemos los primeros usuarios para fines de diseño de interfaz.
        // En un entorno real se debe usar búsqueda asíncrona.
        $usuarios = User::limit(10)->get();

        return view('agent.tickets.create', compact('departamentos', 'prioridades', 'usuarios'));
    }

    /**
     * Almacena un ticket recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $validado = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'dept_id' => 'required|exists:departments,id',
            'priority_id' => 'required|exists:priorities,id',
            'body' => 'nullable|string',
            'walkin_phone' => 'nullable|string|max:20',
            'walkin_address' => 'nullable|string|max:255',
        ]);

        // Generación del número de ticket usando la tabla sequences (desde 1)
        $empresa_id = auth('staff')->user()->empresa_id ?? 1;
        $numeroTicket = $this->ticketService->generateTicketNumber($empresa_id);

        $ticket = new Ticket;
        $ticket->ticket_number = $numeroTicket;
        $ticket->user_id = $validado['user_id'];
        $ticket->subject = $validado['subject'];
        $ticket->dept_id = $validado['dept_id'];
        $ticket->priority_id = $validado['priority_id'];

        // Valores por defecto
        $ticket->empresa_id = auth('staff')->user()->empresa_id ?? 1;
        $ticket->status_id = 1; // Abierto
        $ticket->created = now();

        $ticket->save();

        $this->ticketService->createInitialThread($ticket, $validado['body'] ?? null, auth('staff')->id() ?? 1);

        return redirect()->route('agent.tickets.show', $ticket->id)->with('success', 'Ticket creado correctamente.');
    }

    /**
     * Muestra la vista detallada de un ticket específico.
     */
    public function show($id)
    {
        $ticket = Ticket::with(['user', 'department', 'priority', 'status', 'thread.entries.attachments', 'staff'])->findOrFail($id);

        $departamentos = Department::orderBy('name')->get();
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
            return redirect()->route('agent.tickets.report_sheet', $ticket->id)
                ->with('success', 'Ticket cerrado. Por favor complete la hoja de reporte.');
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

            return redirect()->route('agent.tickets.report_sheet', $ticket->id)
                ->with('success', 'Firma guardada y ticket cerrado correctamente. Por favor complete la hoja de reporte.');
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
