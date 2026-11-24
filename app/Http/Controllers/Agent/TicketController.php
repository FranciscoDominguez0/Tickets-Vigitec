<?php

namespace App\Http\Controllers\Agent;

use App\DataTransferObjects\Ticket\TicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\AdministrarReferidosTicketRequest;
use App\Http\Requests\Agent\BloquearEmailTicketRequest;
use App\Http\Requests\Agent\CambiarPropietarioTicketRequest;
use App\Http\Requests\Agent\CrearRequisicionInventarioRequest;
use App\Http\Requests\Agent\EditarHorasSoporteTicketRequest;
use App\Http\Requests\Agent\GestionarColaboradorTicketRequest;
use App\Http\Requests\Agent\SolicitarRevisionEjecutivaRequest;
use App\Http\Requests\Agent\UnirTicketRequest;
use App\Http\Requests\Agent\VincularTicketRequest;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Models\Staff;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use App\Repositories\DepartmentRepository;
use App\Repositories\TicketRepository;
use App\Services\TicketNotificacionService;
use App\Services\TicketOpcionesService;
use App\Services\TicketPdfService;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService,
        protected TicketOpcionesService $ticketOpcionesService,
        protected TicketRepository $ticketRepository,
        protected DepartmentRepository $departmentRepository,
        protected TicketPdfService $ticketPdfService,
        protected TicketNotificacionService $ticketNotificacionService
    ) {}

    /**
     * Muestra la lista de tickets.
     */
    public function listar(Request $request): View
    {
        $filtro = $request->input('filter');
        $tickets = $this->ticketRepository->obtenerPaginados($filtro, 20);

        $idPersonal = auth('staff')->id() ?? 1;
        $estadisticas = $this->ticketRepository->obtenerEstadisticas($idPersonal);

        return view('agent.tickets.index', compact('tickets', 'estadisticas'));
    }

    public function index(Request $request): View
    {
        return $this->listar($request);
    }

    /**
     * Muestra el formulario para crear un nuevo ticket.
     */
    public function crear(): View
    {
        $datos = $this->ticketService->obtenerDatosParaFormularioCreacion();

        return view('agent.tickets.create', $datos);
    }

    public function create(): View
    {
        return $this->crear();
    }

    /**
     * Almacena un ticket recién creado en la base de datos siguiendo la arquitectura:
     * Form Request -> Controller -> DTO -> Service -> Repository.
     */
    public function guardar(StoreTicketRequest $request): RedirectResponse
    {
        $empresaId = auth('staff')->user()->empresa_id ?? 1;
        $staffId = auth('staff')->id() ?? 1;

        $ticketData = TicketData::desdeSolicitud($request, $empresaId);

        $ticket = $this->ticketService->crearTicket(
            $ticketData,
            $request->input('body'),
            $staffId
        );

        return redirect()
            ->route('agent.tickets.show', $ticket->id)
            ->with('success', 'Ticket creado correctamente.');
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        return $this->guardar($request);
    }

    /**
     * Muestra la vista detallada de un ticket específico.
     */
    public function mostrar($id): View
    {
        $ticket = $this->ticketRepository->buscarConDetalles((int) $id);

        if (! $ticket) {
            abort(404, 'Ticket no encontrado');
        }

        $ticket->loadMissing([
            'vinculos.ticketVinculado.status',
            'referidos.staff',
            'referidos.departamento',
            'aprobaciones.solicitante',
            'aprobaciones.gerente',
            'requisiciones.items',
            'requisiciones.agente',
            'colaboradores',
        ]);

        $departamentos = $this->departmentRepository->obtenerTodos();
        $miembrosStaff = Staff::where('is_active', 1)->orderBy('firstname')->get();
        $estados = TicketStatus::orderBy('id')->get();
        $empresaId = (int) ($ticket->empresa_id ?? 1);
        $usuarios = User::where('empresa_id', $empresaId)->where('status', 1)->orderBy('firstname')->get();
        $gerentes = Staff::where('empresa_id', $empresaId)->where('is_active', 1)->orderBy('firstname')->get();

        return view('agent.tickets.show', compact('ticket', 'departamentos', 'miembrosStaff', 'estados', 'usuarios', 'gerentes'));
    }

    public function show($id): View
    {
        return $this->mostrar($id);
    }

    /**
     * Guarda una respuesta en el hilo del ticket.
     */
    public function responder(Request $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);

        $validado = $request->validate([
            'response_body' => 'required|string',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        $this->ticketService->agregarRespuesta(
            $ticket,
            $validado['response_body'],
            auth('staff')->id() ?? 1,
            $request->file('attachments') ?? []
        );

        return redirect()->route('agent.tickets.show', $ticket->id)->with('success', 'Respuesta enviada correctamente.');
    }

    /**
     * Asigna un ticket a un agente.
     */
    public function asignar(Request $request, $id): RedirectResponse
    {
        $request->validate(['staff_id' => 'required|exists:staff,id']);

        $ticket = Ticket::findOrFail($id);
        $ticket->staff_id = $request->staff_id;
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket asignado correctamente.');
    }

    /**
     * Transfiere el ticket a otro departamento.
     */
    public function transferir(Request $request, $id): RedirectResponse
    {
        $request->validate(['dept_id' => 'required|exists:departments,id']);

        $ticket = Ticket::findOrFail($id);
        $ticket->dept_id = $request->dept_id;
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket transferido exitosamente.');
    }

    /**
     * Actualiza el estado del ticket.
     */
    public function estado(Request $request, $id): RedirectResponse
    {
        $request->validate(['status_id' => 'required|exists:ticket_status,id']);

        $ticket = Ticket::findOrFail($id);

        $this->ticketService->actualizarEstado($ticket, (int) $request->status_id, auth('staff')->id() ?? 1);

        $nuevoEstado = TicketStatus::find((int) $request->status_id);
        $esCerrado = $nuevoEstado && (in_array(strtolower($nuevoEstado->name), ['cerrado', 'resuelto'], true) || (int) $request->status_id === 5);

        if ($esCerrado) {
            return redirect()->back()
                ->with('success', 'Ticket cerrado. Recuerda llenar la hoja de reporte desde la sección "Por facturar".');
        }

        return redirect()->back()->with('success', 'Estado del ticket actualizado.');
    }

    /**
     * Guarda la firma del cliente dibujada en pantalla para cerrar el ticket.
     */
    public function guardarFirma(Request $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);

        if ($request->has('firma_base64') && ! empty($request->firma_base64)) {
            $estadoCerrado = TicketStatus::where('name', 'Cerrado')->first();
            $idCerrado = $estadoCerrado ? (int) $estadoCerrado->id : 5;

            $this->ticketService->guardarFirmaDesdeBase64($ticket, $request->firma_base64);
            $this->ticketService->actualizarEstado($ticket, $idCerrado, auth('staff')->id() ?? 1);

            // Notificar por correo con el PDF y la firma adjunta
            $this->ticketNotificacionService->notificarCierreConFirma($ticket);

            return redirect()->back()
                ->with('success', 'Firma guardada, ticket cerrado correctamente y notificaciones enviadas con el acta en PDF adjunta.');
        }

        return redirect()->back()->with('error', 'No se recibió ninguna firma.');
    }

    /**
     * Descarga el acta de cierre del ticket en formato PDF con la firma digital.
     */
    public function descargarPdf($id): Response
    {
        $ticket = Ticket::findOrFail($id);

        return $this->ticketPdfService->descargarPdfActaCierre($ticket);
    }

    public function requestSignature(Request $request, $id): RedirectResponse
    {
        return $this->guardarFirma($request, $id);
    }

    /**
     * Actualiza el cuerpo de un mensaje del hilo.
     */
    public function actualizarHilo(Request $request, $id): RedirectResponse
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
    public function eliminarHilo($id): RedirectResponse
    {
        $entrada = ThreadEntry::findOrFail($id);
        $entrada->delete();

        return redirect()->back()->with('success', 'Mensaje eliminado del hilo.');
    }

    /**
     * Elimina un ticket por completo y lo archiva en el historial de tickets borrados.
     */
    public function eliminar(Request $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $motivo = trim((string) $request->input('motivo', $request->input('reason', 'Eliminado por el agente')));
        $staffId = auth('staff')->id() ?? 1;

        $this->ticketService->eliminarTicket($ticket, $motivo, $staffId);

        return redirect()->route('agent.tickets.index')->with('success', 'Ticket eliminado correctamente y registrado en el historial de tickets borrados.');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        return $this->eliminar($request, $id);
    }

    /**
     * Modifica el cliente titular o propietario del ticket.
     */
    public function cambiarPropietario(CambiarPropietarioTicketRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->cambiarPropietario(
                $ticket,
                (int) $request->validated('user_id'),
                $staffId,
                $request->validated('motivo')
            );

            return redirect()->back()->with('success', 'Propietario del ticket actualizado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Fusiona un ticket secundario dentro del ticket actual.
     */
    public function unir(UnirTicketRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->unirTickets(
                $ticket,
                $request->validated('ticket_secundario_id'),
                $staffId,
                $request->validated('motivo')
            );

            return redirect()->back()->with('success', 'Tickets fusionados correctamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Vincula un ticket existente con el actual.
     */
    public function vincular(VincularTicketRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->vincularTicket(
                $ticket,
                $request->validated('ticket_vinculado_id'),
                $staffId
            );

            return redirect()->back()->with('success', 'Ticket vinculado exitosamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Desvincula un ticket relacionado.
     */
    public function desvincular($id, $linkId): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->desvincularTicket($ticket, (int) $linkId, $staffId);

            return redirect()->back()->with('success', 'Vínculo eliminado exitosamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Actualiza las horas de soporte dedicadas al ticket.
     */
    public function actualizarHorasSoporte(EditarHorasSoporteTicketRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->actualizarHorasSoporte(
                $ticket,
                $request->validated('support_start'),
                $request->validated('support_end'),
                $staffId
            );

            return redirect()->back()->with('success', 'Horas de soporte guardadas con éxito.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Añade una referencia a personal o departamento.
     */
    public function agregarReferido(AdministrarReferidosTicketRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->agregarReferido(
                $ticket,
                $request->validated('staff_id') ? (int) $request->validated('staff_id') : null,
                $request->validated('dept_id') ? (int) $request->validated('dept_id') : null,
                $staffId
            );

            return redirect()->back()->with('success', 'Referencia registrada con éxito.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Elimina una referencia asignada al ticket.
     */
    public function eliminarReferido($id, $referId): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->eliminarReferido($ticket, (int) $referId, $staffId);

            return redirect()->back()->with('success', 'Referencia eliminada correctamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Genera una requisición de inventario con los artículos solicitados.
     */
    public function crearRequisicion(CrearRequisicionInventarioRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->crearRequisicion(
                $ticket,
                $request->validated('items'),
                $staffId
            );

            return redirect()->back()->with('success', 'Solicitud de inventario creada satisfactoriamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Registra un nuevo colaborador en el ticket.
     */
    public function agregarColaborador(GestionarColaboradorTicketRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->agregarColaborador(
                $ticket,
                $request->validated('email'),
                $request->validated('name'),
                $request->validated('user_id') ? (int) $request->validated('user_id') : null,
                $staffId
            );

            return redirect()->back()->with('success', 'Colaborador agregado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Elimina a un colaborador del ticket.
     */
    public function eliminarColaborador($id, $colaboradorId): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->eliminarColaborador($ticket, (int) $colaboradorId, $staffId);

            return redirect()->back()->with('success', 'Colaborador removido exitosamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Solicita una revisión ejecutiva para el ticket.
     */
    public function solicitarRevision(SolicitarRevisionEjecutivaRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->solicitarRevisionEjecutiva(
                $ticket,
                (int) $request->validated('manager_id'),
                $request->validated('motivo'),
                $staffId
            );

            return redirect()->back()->with('success', 'Revisión ejecutiva solicitada con éxito.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Bloquea el correo electrónico o dominio del cliente del ticket.
     */
    public function bloquearEmail(BloquearEmailTicketRequest $request, $id): RedirectResponse
    {
        $ticket = Ticket::findOrFail($id);
        $staffId = auth('staff')->id() ?? 1;

        try {
            $this->ticketOpcionesService->bloquearEmail(
                $ticket,
                $staffId,
                $request->validated('motivo'),
                $request->boolean('bloquear_dominio')
            );

            return redirect()->back()->with('success', 'Correo electrónico bloqueado exitosamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
