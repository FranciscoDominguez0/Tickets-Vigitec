<?php

namespace App\DataTransferObjects\Ticket;

use App\Http\Requests\Ticket\StoreTicketRequest;

class TicketData
{
    public function __construct(
        public int $userId,
        public string $subject,
        public int $deptId,
        public int $priorityId,
        public int $empresaId,
        public ?string $ticketNumber = null,
        public ?string $body = null,
        public ?string $walkinPhone = null,
        public ?string $walkinAddress = null,
        public int $statusId = 1,
    ) {}

    /**
     * Construye el DTO desde la solicitud validada.
     */
    public static function desdeSolicitud(StoreTicketRequest $solicitud, int $empresaId, ?string $numeroTicket = null): self
    {
        return new self(
            userId: (int) $solicitud->validated('user_id'),
            subject: (string) $solicitud->validated('subject'),
            deptId: (int) $solicitud->validated('dept_id'),
            priorityId: (int) $solicitud->validated('priority_id'),
            empresaId: $empresaId,
            ticketNumber: $numeroTicket,
            body: $solicitud->validated('body'),
            walkinPhone: $solicitud->validated('walkin_phone'),
            walkinAddress: $solicitud->validated('walkin_address'),
            statusId: 1
        );
    }

    public static function fromRequest(StoreTicketRequest $solicitud, int $empresaId, ?string $numeroTicket = null): self
    {
        return self::desdeSolicitud($solicitud, $empresaId, $numeroTicket);
    }

    /**
     * Convierte las propiedades a arreglo para inserción en Eloquent.
     */
    public function aArreglo(): array
    {
        return [
            'ticket_number' => $this->ticketNumber,
            'user_id' => $this->userId,
            'subject' => $this->subject,
            'dept_id' => $this->deptId,
            'priority_id' => $this->priorityId,
            'empresa_id' => $this->empresaId,
            'status_id' => $this->statusId,
            'created' => now(),
        ];
    }

    public function toArray(): array
    {
        return $this->aArreglo();
    }
}
