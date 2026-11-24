<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketLink extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'ticket_links';

    protected $fillable = [
        'ticket_id',
        'linked_ticket_id',
        'empresa_id',
        'created',
    ];

    protected $casts = [
        'created' => 'datetime',
    ];

    /**
     * Ticket principal al que pertenece el vínculo.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Ticket secundario que está vinculado.
     */
    public function ticketVinculado(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'linked_ticket_id');
    }

    /**
     * Empresa asociada al vínculo.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}
