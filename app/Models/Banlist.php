<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banlist extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'banlist';

    public const CREATED_AT = 'created';

    public const UPDATED_AT = 'updated';

    protected $fillable = [
        'empresa_id',
        'email',
        'domain',
        'notes',
        'is_active',
        'created',
        'updated',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    /**
     * Retorna el identificador legible bloqueado (correo o dominio).
     */
    public function obtenerBloqueo(): string
    {
        return ! empty($this->email) ? $this->email : ('@'.ltrim((string) $this->domain, '@'));
    }
}
