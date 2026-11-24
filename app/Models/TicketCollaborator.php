<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCollaborator extends Model
{
    use HasFactory;

    protected $table = 'ticket_collaborators';

    protected $fillable = [
        'ticket_id',
        'empresa_id',
        'user_id',
        'name',
        'email',
        'role',
    ];

    /**
     * Ticket al que pertenece la colaboración.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Usuario registrado en el sistema (si existe).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
