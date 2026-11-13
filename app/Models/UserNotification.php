<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotification extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'user_notifications';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Usuario o cliente destinatario de la notificación.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Ticket relacionado opcional.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
