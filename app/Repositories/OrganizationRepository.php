<?php

namespace App\Repositories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Collection;

class OrganizationRepository
{
    /**
     * Retorna todas las organizaciones ordenadas por nombre.
     */
    public function obtenerTodas(): Collection
    {
        return Organization::orderBy('name')->get();
    }

    /**
     * Busca una organización por su identificador.
     */
    public function buscarPorId(int $id): ?Organization
    {
        return Organization::find($id);
    }

    /**
     * Busca o crea una organización por su nombre.
     */
    public function buscarOCrearPorNombre(string $nombre, int $empresaId = 1): Organization
    {
        return Organization::firstOrCreate(
            ['name' => trim($nombre)],
            ['empresa_id' => $empresaId]
        );
    }
}
