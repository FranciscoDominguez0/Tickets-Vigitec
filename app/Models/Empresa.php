<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estado',
        'fecha_creacion',
        'precio_mensual',
        'fecha_inicio_servicio',
        'fecha_vencimiento',
        'dias_gracia',
        'estado_pago',
        'bloqueada',
        'motivo_bloqueo',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_inicio_servicio' => 'date',
        'fecha_vencimiento' => 'date',
        'bloqueada' => 'boolean',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'empresa_id');
    }

    public function organizaciones()
    {
        return $this->hasMany(Organization::class, 'empresa_id');
    }

    public function departamentos()
    {
        return $this->hasMany(Department::class, 'empresa_id');
    }
}
