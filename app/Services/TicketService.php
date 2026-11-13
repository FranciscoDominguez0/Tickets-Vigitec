<?php

namespace App\Services;

use App\DataTransferObjects\Ticket\TicketData;
use App\Models\Staff;
use App\Models\Thread;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketDeletionRequest;
use App\Models\TicketStatus;
use App\Repositories\DepartmentRepository;
use App\Repositories\PriorityRepository;
use App\Repositories\TicketRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TicketService
{
    public function __construct(
        protected TicketRepository $ticketRepository,
        protected DepartmentRepository $departmentRepository,
        protected PriorityRepository $priorityRepository,
        protected UserRepository $userRepository,
        protected TicketNotificacionService $ticketNotificacionService
    ) {}

    /**
     * Retorna los datos necesarios para renderizar el formulario de creación de ticket.
     */
    public function obtenerDatosParaFormularioCreacion(): array
    {
        return [
            'departamentos' => $this->departmentRepository->obtenerActivos(),
            'prioridades' => $this->priorityRepository->obtenerTodas(),
            'usuarios' => $this->userRepository->obtenerFiltrados(porPagina: 50),
        ];
    }

    /**
     * Coordina la creación completa de un ticket utilizando DTO y Repositorio.
     */
    public function crearTicket(TicketData $datosTicket, ?string $cuerpoInicial = null, ?int $staffId = null): Ticket
    {
        $numeroTicket = $this->generarNumeroTicket($datosTicket->empresaId);
        $datosTicket->ticketNumber = $numeroTicket;

        // Asegurar que el estado asignado exista en la base de datos
        $estado = TicketStatus::find($datosTicket->statusId);
        if (! $estado) {
            TicketStatus::firstOrCreate(
                ['id' => $datosTicket->statusId],
                ['name' => 'Abierto', 'color' => '#3498db', 'icon' => 'fa-folder-open', 'order_by' => 1]
            );
        }

        $ticket = $this->ticketRepository->crear($datosTicket->toArray());

        if (! empty($cuerpoInicial)) {
            $this->crearHiloInicial($ticket, $cuerpoInicial, $staffId ?? 1);
        }

        $this->ticketNotificacionService->notificarCreacion($ticket, $cuerpoInicial);

        return $ticket;
    }

    /**
     * Genera el siguiente número de secuencia para tickets.
     */
    public function generarNumeroTicket(int $empresaId): string
    {
        return DB::transaction(function () use ($empresaId) {
            $secuencia = DB::table('sequences')
                ->where('empresa_id', $empresaId)
                ->where('name', 'tickets')
                ->lockForUpdate()
                ->first();

            if (! $secuencia) {
                DB::table('sequences')->insert([
                    'empresa_id' => $empresaId,
                    'name' => 'tickets',
                    'next' => 2,
                    'increment' => 1,
                    'padding' => 0,
                    'created' => now(),
                    'updated' => now(),
                ]);

                return '1';
            }

            DB::table('sequences')
                ->where('id', $secuencia->id)
                ->update([
                    'next' => $secuencia->next + $secuencia->increment,
                    'updated' => now(),
                ]);

            return (string) $secuencia->next;
        });
    }

    public function generateTicketNumber(int $empresaId): string
    {
        return $this->generarNumeroTicket($empresaId);
    }

    /**
     * Crea el hilo inicial del ticket.
     */
    public function crearHiloInicial(Ticket $ticket, ?string $body, ?int $staffId = null): ?Thread
    {
        if (empty($body)) {
            return null;
        }

        $hilo = $ticket->thread()->create([
            'created' => now(),
        ]);

        $hilo->entries()->create([
            'empresa_id' => $ticket->empresa_id,
            'staff_id' => $staffId,
            'body' => $body,
            'is_internal' => 0,
            'created' => now(),
        ]);

        return $hilo;
    }

    public function createInitialThread(Ticket $ticket, ?string $body, ?int $staffId = null): ?Thread
    {
        return $this->crearHiloInicial($ticket, $body, $staffId);
    }

    /**
     * Añade una respuesta al hilo del ticket, opcionalmente con adjuntos.
     */
    public function agregarRespuesta(Ticket $ticket, string $body, ?int $staffId = null, array $attachments = []): ThreadEntry
    {
        $hilo = $ticket->thread;

        if (! $hilo) {
            $hilo = $ticket->thread()->create([
                'created' => now(),
            ]);
        }

        $entrada = $hilo->entries()->create([
            'empresa_id' => $ticket->empresa_id,
            'staff_id' => $staffId,
            'body' => $body,
            'is_internal' => 0,
            'created' => now(),
        ]);

        if (! empty($attachments)) {
            foreach ($attachments as $archivo) {
                if ($archivo instanceof UploadedFile) {
                    $ruta = $archivo->store('tickets/attachments');

                    $entrada->attachments()->create([
                        'empresa_id' => $ticket->empresa_id,
                        'filename' => $archivo->hashName(),
                        'original_filename' => $archivo->getClientOriginalName(),
                        'mimetype' => $archivo->getMimeType(),
                        'size' => $archivo->getSize(),
                        'path' => $ruta,
                        'created' => now(),
                    ]);
                }
            }
        }

        return $entrada;
    }

    public function addReply(Ticket $ticket, string $body, ?int $staffId = null, array $attachments = []): ThreadEntry
    {
        return $this->agregarRespuesta($ticket, $body, $staffId, $attachments);
    }

    /**
     * Actualiza el estado del ticket y realiza acciones secundarias.
     */
    public function actualizarEstado(Ticket $ticket, int $statusId, ?int $staffId = null): Ticket
    {
        $estadoAnteriorId = (int) ($ticket->getOriginal('status_id') ?: $ticket->status_id);
        $estadoAnterior = TicketStatus::find($estadoAnteriorId);
        $nombreEstadoAnterior = $estadoAnterior ? $estadoAnterior->name : 'Abierto';

        $ticket->status_id = $statusId;

        $nuevoEstado = TicketStatus::find($statusId);
        $nombreEstado = strtolower((string) optional($nuevoEstado)->name);
        $esCerrado = in_array($nombreEstado, ['cerrado', 'resuelto'], true) || ($statusId == 5);

        if ($esCerrado) {
            $ticket->closed = now();
            $ticket->closed_at = now();
        } else {
            $ticket->closed = null;
            $ticket->closed_at = null;
        }

        if ($nuevoEstado && in_array($nuevoEstado->name, ['En camino', 'En proceso'])) {
            if (! $ticket->staff_id || $ticket->staff_id == 0) {
                $ticket->staff_id = $staffId ?? 1;
            }

            DB::table('staff_locations')->updateOrInsert(
                ['staff_id' => $ticket->staff_id],
                [
                    'lat' => 8.9824 + (rand(-10, 10) * 0.002),
                    'lng' => -79.5199 + (rand(-10, 10) * 0.002),
                    'updated_at' => now(),
                ]
            );
        }

        $ticket->save();

        if ($estadoAnteriorId > 0 && $estadoAnteriorId !== $statusId) {
            $nombreResponsable = null;
            if ($staffId) {
                $staff = Staff::find($staffId);
                if ($staff) {
                    $nombreResponsable = trim($staff->firstname.' '.$staff->lastname);
                }
            }

            $this->ticketNotificacionService->notificarCambioEstado(
                $ticket,
                $nombreEstadoAnterior,
                $nuevoEstado ? $nuevoEstado->name : 'Estado #'.$statusId,
                $nombreResponsable
            );
        }

        return $ticket;
    }

    public function updateStatus(Ticket $ticket, int $statusId, ?int $staffId = null): Ticket
    {
        return $this->actualizarEstado($ticket, $statusId, $staffId);
    }

    /**
     * Procesa y guarda la firma en formato Base64.
     */
    public function guardarFirmaDesdeBase64(Ticket $ticket, string $base64Image): Ticket
    {
        $partes = explode(';base64,', $base64Image);
        $tipoAux = explode('image/', $partes[0]);
        $tipoImagen = $tipoAux[1] ?? 'png';
        $imagenBase64 = base64_decode($partes[1]);

        $nombreArchivo = 'tickets/signatures/ticket_'.$ticket->id.'_'.time().'.'.$tipoImagen;

        Storage::disk('public')->put($nombreArchivo, $imagenBase64);

        $ticket->client_signature = $nombreArchivo;
        $ticket->save();

        return $ticket;
    }

    public function saveSignatureFromBase64(Ticket $ticket, string $base64Image): Ticket
    {
        return $this->guardarFirmaDesdeBase64($ticket, $base64Image);
    }

    /**
     * Crea un nuevo ticket solicitado directamente por un cliente.
     */
    public function crearTicketCliente(
        int $usuarioId,
        int $empresaId,
        int $departamentoId,
        string $asunto,
        string $cuerpo,
        ?int $temaId = null,
        ?string $anydesk = null,
        array $adjuntos = []
    ): Ticket {
        $numeroTicket = $this->generarNumeroTicket($empresaId);

        $ticket = $this->ticketRepository->crear([
            'ticket_number' => $numeroTicket,
            'empresa_id' => $empresaId,
            'user_id' => $usuarioId,
            'dept_id' => $departamentoId,
            'topic_id' => $temaId,
            'status_id' => 1, // Abierto
            'priority_id' => 1, // Baja/Normal
            'subject' => $asunto,
            'anydesk' => $anydesk,
            'created' => now(),
            'updated' => now(),
        ]);

        $hilo = $ticket->thread()->create([
            'empresa_id' => $empresaId,
            'created' => now(),
        ]);

        $entrada = $hilo->entries()->create([
            'empresa_id' => $empresaId,
            'user_id' => $usuarioId,
            'body' => $cuerpo,
            'is_internal' => 0,
            'created' => now(),
            'updated' => now(),
        ]);

        if (! empty($adjuntos)) {
            foreach ($adjuntos as $archivo) {
                if ($archivo instanceof UploadedFile) {
                    $ruta = $archivo->store('tickets/attachments', 'public');

                    $entrada->attachments()->create([
                        'empresa_id' => $empresaId,
                        'filename' => $archivo->hashName(),
                        'original_filename' => $archivo->getClientOriginalName(),
                        'mimetype' => $archivo->getMimeType(),
                        'size' => $archivo->getSize(),
                        'path' => $ruta,
                        'created' => now(),
                    ]);
                }
            }
        }

        $this->ticketNotificacionService->notificarCreacion($ticket, $cuerpo);

        return $ticket;
    }

    /**
     * Agrega una respuesta del cliente al hilo del ticket.
     */
    public function responderCliente(
        Ticket $ticket,
        int $usuarioId,
        string $cuerpo,
        array $adjuntos = []
    ): ThreadEntry {
        $hilo = $ticket->thread;
        if (! $hilo) {
            $hilo = $ticket->thread()->create([
                'empresa_id' => $ticket->empresa_id,
                'created' => now(),
            ]);
        }

        $entrada = $hilo->entries()->create([
            'empresa_id' => $ticket->empresa_id,
            'user_id' => $usuarioId,
            'body' => $cuerpo,
            'is_internal' => 0,
            'created' => now(),
            'updated' => now(),
        ]);

        if (! empty($adjuntos)) {
            foreach ($adjuntos as $archivo) {
                if ($archivo instanceof UploadedFile) {
                    $ruta = $archivo->store('tickets/attachments', 'public');

                    $entrada->attachments()->create([
                        'empresa_id' => $ticket->empresa_id,
                        'filename' => $archivo->hashName(),
                        'original_filename' => $archivo->getClientOriginalName(),
                        'mimetype' => $archivo->getMimeType(),
                        'size' => $archivo->getSize(),
                        'path' => $ruta,
                        'created' => now(),
                    ]);
                }
            }
        }

        $ticket->update(['updated' => now()]);

        return $entrada;
    }

    /**
     * Elimina un ticket de la base de datos registrando su instantánea en el historial de borrados.
     */
    public function eliminarTicket(Ticket $ticket, string $motivo, int $staffId): TicketDeletionRequest
    {
        $empresaId = (int) ($ticket->empresa_id ?? 1);

        $ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff', 'thread.entries']);

        $payload = [
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'empresa_id' => $empresaId,
            'user_id' => $ticket->user_id,
            'staff_id' => $ticket->staff_id,
            'dept_id' => $ticket->dept_id,
            'status_id' => $ticket->status_id,
            'priority_id' => $ticket->priority_id,
            'subject' => $ticket->subject,
            'anydesk' => $ticket->anydesk,
            'client_signature' => $ticket->client_signature,
            'close_message' => $ticket->close_message,
            'created' => $ticket->created ? $ticket->created->toISOString() : null,
            'closed' => $ticket->closed ? $ticket->closed->toISOString() : null,
            'user_name' => optional($ticket->user)->name,
            'user_email' => optional($ticket->user)->email,
            'dept_name' => optional($ticket->department)->name,
            'staff_name' => optional($ticket->staff)->firstname.' '.optional($ticket->staff)->lastname,
            'thread_entries' => $ticket->thread && $ticket->thread->entries
                ? $ticket->thread->entries->map(function ($entry) {
                    return [
                        'user_id' => $entry->user_id,
                        'staff_id' => $entry->staff_id,
                        'body' => $entry->body,
                        'is_internal' => $entry->is_internal,
                        'created' => $entry->created ? $entry->created->toISOString() : null,
                    ];
                })->toArray()
                : [],
        ];

        $registro = TicketDeletionRequest::create([
            'ticket_id' => $ticket->id,
            'empresa_id' => $empresaId,
            'ticket_number' => $ticket->ticket_number,
            'ticket_subject' => $ticket->subject,
            'requested_by' => $staffId,
            'reason' => $motivo ?: 'Eliminado por el agente',
            'status' => 'approved',
            'ticket_payload' => $payload,
            'created_at' => now(),
            'resolved_at' => now(),
            'resolved_by' => $staffId,
        ]);

        $ticket->delete();

        return $registro;
    }

    /**
     * Restaura un ticket eliminado a partir de la instantánea guardada en el historial.
     */
    public function restaurarTicket(int $idSolicitud, int $staffIdAdmin): Ticket
    {
        $solicitud = TicketDeletionRequest::findOrFail($idSolicitud);
        $payload = $solicitud->ticket_payload ?? [];

        $ticket = Ticket::create([
            'ticket_number' => $solicitud->ticket_number,
            'empresa_id' => $solicitud->empresa_id,
            'user_id' => $payload['user_id'] ?? null,
            'staff_id' => $payload['staff_id'] ?? null,
            'dept_id' => $payload['dept_id'] ?? 1,
            'status_id' => $payload['status_id'] ?? 1,
            'priority_id' => $payload['priority_id'] ?? 1,
            'subject' => $solicitud->ticket_subject,
            'anydesk' => $payload['anydesk'] ?? null,
            'client_signature' => $payload['client_signature'] ?? null,
            'close_message' => $payload['close_message'] ?? null,
            'created' => $payload['created'] ?? now(),
            'updated' => now(),
            'closed' => $payload['closed'] ?? null,
        ]);

        if (! empty($payload['thread_entries'])) {
            $hilo = $ticket->thread()->create([
                'empresa_id' => $solicitud->empresa_id,
                'created' => now(),
            ]);

            foreach ($payload['thread_entries'] as $entryData) {
                $hilo->entries()->create([
                    'empresa_id' => $solicitud->empresa_id,
                    'user_id' => $entryData['user_id'] ?? null,
                    'staff_id' => $entryData['staff_id'] ?? null,
                    'body' => $entryData['body'] ?? '',
                    'is_internal' => $entryData['is_internal'] ?? 0,
                    'created' => $entryData['created'] ?? now(),
                    'updated' => now(),
                ]);
            }
        }

        $solicitud->update([
            'status' => 'rejected', // Marcado como restaurado
            'resolved_at' => now(),
            'resolved_by' => $staffIdAdmin,
        ]);

        return $ticket;
    }

    /**
     * Purga definitivamente el registro de borrado del historial.
     */
    public function purgarSolicitudBorrado(int $idSolicitud): void
    {
        $solicitud = TicketDeletionRequest::findOrFail($idSolicitud);
        $solicitud->delete();
    }
}
