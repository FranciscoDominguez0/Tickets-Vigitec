<?php

namespace App\Services;

use App\DataTransferObjects\Ticket\TicketData;
use App\Models\Thread;
use App\Models\ThreadEntry;
use App\Models\Ticket;
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
        protected UserRepository $userRepository
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
        $ticket->status_id = $statusId;

        if ($statusId == 3) {
            $ticket->closed = now();
        }

        $nuevoEstado = TicketStatus::find($statusId);

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
}
