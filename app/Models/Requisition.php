<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requisition extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'requisitions';

    protected $fillable = [
        'ticket_id',
        'empresa_id',
        'agent_id',
        'client_id',
        'client_name',
        'status',
        'admin_id_delivered',
        'agent_signature',
        'created_at',
        'delivered_at',
        'signed_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'delivered_at' => 'datetime',
        'signed_at' => 'datetime',
    ];

    /**
     * Ticket al que pertenece la requisición de inventario.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Agente que elaboró la solicitud de requisición.
     */
    public function agente(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'agent_id');
    }

    /**
     * Cliente o solicitante asociado a la requisición.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Administrador que realizó la entrega física de los materiales.
     */
    public function administradorEntrega(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'admin_id_delivered');
    }

    /**
     * Artículos o ítems pertenecientes a esta requisición.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RequisitionItem::class, 'requisition_id');
    }

    /**
     * Comprueba si la requisición se encuentra entregada.
     */
    public function estaEntregada(): bool
    {
        return $this->status === 'delivered';
    }
}
