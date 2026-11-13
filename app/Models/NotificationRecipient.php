<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRecipient extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'notification_recipients';

    public const CREATED_AT = 'created_at';

    public const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Agente o personal configurado para recibir alertas del sistema.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
