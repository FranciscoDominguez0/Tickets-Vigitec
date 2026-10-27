<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    public const CREATED_AT = 'created';

    public const UPDATED_AT = 'updated';

    protected $fillable = [
        'name',
        'empresa_id',
        'address',
        'phone',
        'phone_ext',
        'website',
        'notes',
        'plain_text_emails',
    ];

    protected $casts = [
        'created' => 'datetime',
        'updated' => 'datetime',
        'plain_text_emails' => 'boolean',
    ];

    /**
     * Retorna los usuarios que pertenecen a la organización.
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_organizations', 'organization_id', 'user_id')
            ->withPivot('created_at');
    }

    /**
     * Retorna los usuarios directos asociados por clave foránea.
     */
    public function usuariosDirectos(): HasMany
    {
        return $this->hasMany(User::class, 'organization_id');
    }

    /**
     * Retorna la empresa matriz asociada.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /**
     * Retorna el supervisor o jefe de organización si está configurado.
     */
    public function getJefeAttribute(): ?User
    {
        return $this->usuarios()->where('org_tickets_view', 1)->first()
            ?? User::where('company', $this->name)->where('org_tickets_view', 1)->first();
    }
}
