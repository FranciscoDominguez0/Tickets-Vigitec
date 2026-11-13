<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'roles';

    public const CREATED_AT = 'created';

    public const UPDATED_AT = 'updated';

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empresa_id',
        'name',
        'is_enabled',
    ];

    /**
     * Conversión de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'created' => 'datetime',
        'updated' => 'datetime',
    ];

    /**
     * Relación con el personal que posee este rol.
     */
    public function agentes(): HasMany
    {
        return $this->hasMany(Staff::class, 'role', 'name')
            ->where('empresa_id', $this->empresa_id);
    }

    /**
     * Relación con los permisos asignados a este rol.
     */
    public function permisos(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'role_name', 'name')
            ->where('empresa_id', $this->empresa_id);
    }

    /**
     * Verifica si el rol está siendo utilizado por algún agente.
     */
    public function estaEnUso(): bool
    {
        return Staff::where('empresa_id', $this->empresa_id)
            ->where('role', $this->name)
            ->exists();
    }

    /**
     * Total de agentes asignados a este rol.
     */
    public function totalAgentes(): int
    {
        return Staff::where('empresa_id', $this->empresa_id)
            ->where('role', $this->name)
            ->count();
    }

    /**
     * Total de agentes activos asignados a este rol.
     */
    public function totalAgentesActivos(): int
    {
        return Staff::where('empresa_id', $this->empresa_id)
            ->where('role', $this->name)
            ->where('is_active', true)
            ->count();
    }
}
