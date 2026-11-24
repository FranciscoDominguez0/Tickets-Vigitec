<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketApproval extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'ticket_approvals';

    protected $fillable = [
        'ticket_id',
        'requested_by_staff_id',
        'manager_id',
        'status',
        'created_at',
        'resolved_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Ticket asociado a la solicitud de aprobación o revisión ejecutiva.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Agente que solicitó la revisión ejecutiva.
     */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'requested_by_staff_id');
    }

    /**
     * Gerente o administrador asignado para responder a la revisión.
     */
    public function gerente(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'manager_id');
    }

    /**
     * Verifica si la revisión se encuentra pendiente.
     */
    public function estaPendiente(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Verifica si la revisión fue aprobada.
     */
    public function estaAprobado(): bool
    {
        return $this->status === 'aprobado';
    }
}
