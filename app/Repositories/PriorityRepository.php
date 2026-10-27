<?php

namespace App\Repositories;

use App\Models\Priority;
use Illuminate\Database\Eloquent\Collection;

class PriorityRepository
{
    /**
     * Retorna todas las prioridades ordenadas por nivel o identificador.
     */
    public function obtenerTodas(): Collection
    {
        return Priority::orderBy('id')->get();
    }

    /**
     * Busca una prioridad por su identificador.
     */
    public function buscarPorId(int $id): ?Priority
    {
        return Priority::find($id);
    }
}
