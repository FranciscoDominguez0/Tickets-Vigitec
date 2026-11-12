<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'sequences';

    public const CREATED_AT = 'created';

    public const UPDATED_AT = 'updated';

    protected $fillable = [
        'empresa_id',
        'name',
        'next',
        'increment',
        'padding',
        'created',
        'updated',
    ];

    protected $casts = [
        'next' => 'integer',
        'increment' => 'integer',
        'padding' => 'integer',
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    /**
     * Genera la representación formateada del número actual con el relleno configurado.
     */
    public function obtenerNumeroFormateado(): string
    {
        if ($this->padding > 0) {
            return str_pad((string) $this->next, $this->padding, '0', STR_PAD_LEFT);
        }

        return (string) $this->next;
    }
}
