<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable, Tenantable;

    protected $table = 'staff';

    // Personalización de timestamps según la base de datos importada
    public const CREATED_AT = 'created';

    public const UPDATED_AT = 'updated';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'firstname',
        'lastname',
        'dept_id',
        'empresa_id',
        'role',
        'is_active',
        'last_login',
        'signature',
        'dark_mode',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'created' => 'datetime',
        'updated' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
        'dark_mode' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function departamento()
    {
        return $this->department();
    }

    public function departamentosAdicionales()
    {
        return $this->belongsToMany(Department::class, 'staff_departments', 'staff_id', 'dept_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'staff_id');
    }

    /**
     * Retorna el nombre completo del agente.
     */
    public function nombreCompleto(): string
    {
        return trim("{$this->firstname} {$this->lastname}");
    }

    /**
     * Retorna el total de tickets asignados al agente.
     */
    public function totalTickets(): int
    {
        return Ticket::where('staff_id', $this->id)->count();
    }

    /**
     * Determina si el agente tiene tickets asociados en el sistema.
     */
    public function estaEnUso(): bool
    {
        return Ticket::where('staff_id', $this->id)->exists();
    }

    /**
     * Determina si el miembro del personal tiene rol de administrador.
     */
    public function esAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin'], true);
    }
}
