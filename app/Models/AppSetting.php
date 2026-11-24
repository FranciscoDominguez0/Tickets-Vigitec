<?php

namespace App\Models;

use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Obtiene un conjunto de configuraciones asociadas a un prefijo para la empresa especificada en una única consulta.
     *
     * @return array<string, mixed>
     */
    public static function obtenerGrupo(string $prefijo, ?int $empresaId = null): array
    {
        $idEmpresa = $empresaId ?? (auth('staff')->check() ? auth('staff')->user()->empresa_id : (session('empresa_id', 1)));

        return static::withoutGlobalScopes()
            ->where('empresa_id', $idEmpresa)
            ->where('key', 'like', $prefijo.'%')
            ->pluck('value', 'key')
            ->all();
    }

    /**
     * Guarda múltiples configuraciones dentro de una transacción de base de datos.
     *
     * @param  array<string, mixed>  $valores
     */
    public static function establecerMuchos(array $valores, ?int $empresaId = null): void
    {
        $idEmpresa = $empresaId ?? (auth('staff')->check() ? auth('staff')->user()->empresa_id : (session('empresa_id', 1)));
        $ahora = now();

        DB::transaction(function () use ($valores, $idEmpresa, $ahora) {
            foreach ($valores as $clave => $valor) {
                static::withoutGlobalScopes()->updateOrCreate(
                    ['empresa_id' => $idEmpresa, 'key' => $clave],
                    ['value' => $valor, 'updated' => $ahora]
                );
            }
        });
    }
}
