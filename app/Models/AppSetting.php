<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory, Tenantable;

    protected $table = 'app_settings';

    public $timestamps = false;

    protected $fillable = [
        'key',
        'empresa_id',
        'value',
        'updated',
    ];

    protected $casts = [
        'updated' => 'datetime',
    ];

    /**
     * Obtiene el valor de una configuración por clave y empresa.
     */
    public static function obtener(string $clave, mixed $predeterminado = null, ?int $empresaId = null): mixed
    {
        $idEmpresa = $empresaId ?? (auth('staff')->check() ? auth('staff')->user()->empresa_id : (session('empresa_id', 1)));

        $configuracion = static::withoutGlobalScopes()
            ->where('empresa_id', $idEmpresa)
            ->where('key', $clave)
            ->first();

        return $configuracion ? $configuracion->value : $predeterminado;
    }

    /**
     * Guarda o actualiza una configuración para la empresa actual.
     */
    public static function establecer(string $clave, mixed $valor, ?int $empresaId = null): void
    {
        $idEmpresa = $empresaId ?? (auth('staff')->check() ? auth('staff')->user()->empresa_id : (session('empresa_id', 1)));

        static::withoutGlobalScopes()->updateOrCreate(
            ['empresa_id' => $idEmpresa, 'key' => $clave],
            ['value' => $valor, 'updated' => now()]
        );
    }
}

