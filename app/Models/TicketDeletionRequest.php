<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketDeletionRequest extends Model
{
    public $timestamps = false;

    protected $table = 'ticket_deletion_requests';

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'resolved_at' => 'datetime',
        'ticket_payload' => 'array',
    ];

    /**
     * Agente que solicitó o ejecutó el borrado del ticket.
     */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'requested_by');
    }

    /**
     * Administrador que resolvió la solicitud de borrado.
     */
    public function resueltoPor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'resolved_by');
    }

    /**
     * Empresa a la que pertenece la solicitud.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /**
     * Ticket asociado (si aún existe en la base de datos).
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
