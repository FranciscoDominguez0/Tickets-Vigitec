<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository
{
    /**
     * Retorna todos los departamentos activos ordenados por nombre.
     */
    public function obtenerActivos(): Collection
    {
        return Department::where('is_active', 1)->orderBy('name')->get();
    }

    /**
     * Retorna todos los departamentos registrados.
     */
    public function obtenerTodos(): Collection
    {
        return Department::orderBy('name')->get();
    }

    /**
     * Busca un departamento por su identificador.
     */
    public function buscarPorId(int $id): ?Department
    {
        return Department::find($id);
    }
}
