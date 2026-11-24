<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReferral extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'ticket_referrals';

    protected $fillable = [
        'ticket_id',
        'staff_id',
        'dept_id',
        'created',
    ];

    protected $casts = [
        'created' => 'datetime',
    ];

    /**
     * Ticket al que se asocia la referencia.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Miembro del personal al que fue referido el ticket (si aplica).
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Departamento al que fue referido el ticket (si aplica).
     */
    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }
}
