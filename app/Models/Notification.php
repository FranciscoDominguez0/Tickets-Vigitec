<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'notifications';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Agente o personal destinatario de la notificación interna.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Ticket relacionado opcional cuando el tipo es relativo a un ticket.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'related_id');
    }
}
