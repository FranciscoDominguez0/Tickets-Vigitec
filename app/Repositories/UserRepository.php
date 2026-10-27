<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Obtiene los usuarios con filtros y conteos de tickets.
     */
    public function obtenerFiltrados(?string $busqueda = null, ?string $estado = null, int $porPagina = 15): LengthAwarePaginator
    {
        $consulta = User::query()
            ->withCount([
                'tickets as total_tickets',
                'tickets as tickets_abiertos' => function ($query) {
                    $query->whereIn('status_id', [1, 2]); // Abierto o En proceso
                },
            ]);

        if ($busqueda) {
            $consulta->where(function ($query) use ($busqueda) {
                $query->where('firstname', 'like', "%{$busqueda}%")
                    ->orWhere('lastname', 'like', "%{$busqueda}%")
                    ->orWhere('email', 'like', "%{$busqueda}%")
                    ->orWhere('phone', 'like', "%{$busqueda}%")
                    ->orWhere('company', 'like', "%{$busqueda}%");
            });
        }

        if ($estado !== null && $estado !== '') {
            $valEstado = in_array((string) $estado, ['1', 'active'], true) ? 'active' : 'inactive';
            $consulta->where('status', $valEstado);
        }

        return $consulta->orderByDesc('id')->paginate($porPagina);
    }

    /**
     * Busca un usuario por su identificador primario.
     */
    public function buscarPorId(int $id): ?User
    {
        return User::with(['tickets' => function ($query) {
            $query->with(['department', 'priority', 'status', 'staff'])->orderByDesc('created');
        }])->find($id);
    }

    /**
     * Busca un usuario por correo electrónico.
     */
    public function buscarPorEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->buscarPorEmail($email);
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     */
    public function crear(array $datos): User
    {
        return User::create($datos);
    }

    public function create(array $datos): User
    {
        return $this->crear($datos);
    }

    /**
     * Actualiza un usuario existente.
     */
    public function actualizar(User $usuario, array $datos): bool
    {
        return $usuario->update($datos);
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function eliminar(User $usuario): bool
    {
        return (bool) $usuario->delete();
    }

    /**
     * Obtiene métricas generales de usuarios.
     */
    public function obtenerEstadisticas(): array
    {
        return [
            'total' => User::count(),
            'activos' => User::whereIn('status', ['active', 1])->count(),
            'inactivos' => User::whereIn('status', ['inactive', 'banned', 0])->count(),
        ];
    }
}
