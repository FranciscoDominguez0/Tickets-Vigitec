<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UserService
{
    public function __construct(
        protected UserRepository $repositorio
    ) {}

    /**
     * Retorna el listado paginado de usuarios con filtros aplicados.
     */
    public function obtenerDirectorio(?string $busqueda = null, ?string $estado = null, int $porPagina = 15): LengthAwarePaginator
    {
        return $this->repositorio->obtenerFiltrados($busqueda, $estado, $porPagina);
    }

    /**
     * Retorna las estadísticas generales de los usuarios.
     */
    public function obtenerEstadisticas(): array
    {
        return $this->repositorio->obtenerEstadisticas();
    }

    /**
     * Encuentra y retorna un usuario con su historial de tickets.
     */
    public function obtenerPorId(int $id): User
    {
        $usuario = $this->repositorio->buscarPorId($id);

        if (! $usuario) {
            throw new InvalidArgumentException('El usuario solicitado no existe.');
        }

        return $usuario;
    }

    /**
     * Procesa y registra un nuevo usuario.
     */
    public function registrarUsuario(array $datos): User
    {
        $password = ! empty($datos['password']) ? $datos['password'] : 'password123';
        $estado = 'active';
        if (isset($datos['status'])) {
            $estado = in_array((string) $datos['status'], ['inactive', '0'], true) ? 'inactive' : 'active';
        }

        $datosFormateados = [
            'firstname' => trim($datos['firstname']),
            'lastname' => trim($datos['lastname']),
            'email' => strtolower(trim($datos['email'])),
            'phone' => $datos['phone'] ?? null,
            'company' => $datos['company'] ?? null,
            'address' => $datos['address'] ?? null,
            'status' => $estado,
            'password' => Hash::make($password),
            'empresa_id' => auth('staff')->user()->empresa_id ?? 1,
        ];

        return $this->repositorio->crear($datosFormateados);
    }

    /**
     * Actualiza la información de un usuario.
     */
    public function actualizarUsuario(int $id, array $datos): User
    {
        $usuario = $this->obtenerPorId($id);

        $datosActualizar = [
            'firstname' => trim($datos['firstname']),
            'lastname' => trim($datos['lastname']),
            'email' => strtolower(trim($datos['email'])),
            'phone' => $datos['phone'] ?? null,
            'company' => $datos['company'] ?? null,
            'address' => $datos['address'] ?? null,
        ];

        if (isset($datos['status'])) {
            $datosActualizar['status'] = in_array((string) $datos['status'], ['inactive', '0'], true) ? 'inactive' : 'active';
        }

        if (! empty($datos['password'])) {
            $datosActualizar['password'] = Hash::make($datos['password']);
        }

        $this->repositorio->actualizar($usuario, $datosActualizar);

        return $usuario->fresh();
    }

    /**
     * Cambia el estado activo/inactivo del usuario.
     */
    public function conmutarEstado(int $id): User
    {
        $usuario = $this->obtenerPorId($id);
        $nuevoEstado = $usuario->esActivo() ? 'inactive' : 'active';

        $this->repositorio->actualizar($usuario, ['status' => $nuevoEstado]);

        return $usuario->fresh();
    }

    /**
     * Elimina el usuario si no tiene tickets asociados.
     */
    public function eliminarUsuario(int $id): void
    {
        $usuario = $this->obtenerPorId($id);

        if ($usuario->tickets()->exists()) {
            throw new InvalidArgumentException('No se puede eliminar el usuario porque tiene tickets registrados.');
        }

        $this->repositorio->eliminar($usuario);
    }
}
