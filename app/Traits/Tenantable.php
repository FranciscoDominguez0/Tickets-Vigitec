<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Tenantable
{
    protected static function bootTenantable()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            // El helper session() obtendrá el empresa_id cuando el Auth de Laravel esté configurado.
            if (session()->has('empresa_id')) {
                $builder->where('empresa_id', session('empresa_id'));
            }
        });
    }
}
