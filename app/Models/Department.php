<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Department extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'departments';

    public $timestamps = false;

    /**
     * Atributos asignables de forma masiva.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'empresa_id',
        'name',
        'description',
        'is_active',
        'requires_report',
        'default_staff_id',
        'created',
    ];

    /**
     * Conversión de tipos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'requires_report' => 'boolean',
        'created' => 'datetime',
    ];

    /**
     * Agentes asignados como miembros secundarios del departamento.
     */
    public function agentes(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'staff_departments', 'dept_id', 'staff_id');
    }

    /**
     * Agentes que tienen este departamento como su departamento principal.
     */
    public function agentesPrimarios(): HasMany
    {
        return $this->hasMany(Staff::class, 'dept_id');
    }

    /**
     * Tickets asociados a este departamento.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'dept_id');
    }

    /**
     * Cuenta de correo asociada a este departamento.
     */
    public function cuentaCorreo(): HasOne
    {
        return $this->hasOne(EmailAccount::class, 'dept_id');
    }

    /**
     * Temas de ayuda asociados a este departamento.
     */
    public function temasAyuda(): HasMany
    {
        return $this->hasMany(HelpTopic::class, 'dept_id');
    }

    /**
     * Obtiene el total de agentes únicos asociados (principales o secundarios en la misma empresa).
     */
    public function totalAgentes(): int
    {
        $idSecundarios = DB::table('staff_departments')
            ->join('staff', 'staff.id', '=', 'staff_departments.staff_id')
            ->where('staff_departments.dept_id', $this->id)
            ->where('staff.empresa_id', $this->empresa_id)
            ->pluck('staff.id');

        $idPrimarios = Staff::where('dept_id', $this->id)
            ->where('empresa_id', $this->empresa_id)
            ->pluck('id');

        return $idSecundarios->merge($idPrimarios)->unique()->count();
    }

    /**
     * Obtiene el total de tickets asignados a este departamento en la empresa.
     */
    public function totalTickets(): int
    {
        return Ticket::where('dept_id', $this->id)
            ->where('empresa_id', $this->empresa_id)
            ->count();
    }

    /**
     * Determina si el departamento está en uso y no debe ser eliminado.
     */
    public function estaEnUso(): bool
    {
        if ($this->totalAgentes() > 0) {
            return true;
        }

        if ($this->totalTickets() > 0) {
            return true;
        }

        return HelpTopic::where('dept_id', $this->id)
            ->where('empresa_id', $this->empresa_id)
            ->exists();
    }
}
