<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Repositories\OrganizationRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class OrganizationService
{
    public function __construct(
        protected OrganizationRepository $repositorio
    ) {}

    /**
     * Retorna el listado paginado de organizaciones con búsqueda.
     */
    public function obtenerListado(?string $busqueda = null, int $porPagina = 12): LengthAwarePaginator
    {
        return $this->repositorio->obtenerFiltradas($busqueda, $porPagina);
    }

    /**
     * Retorna las métricas y estadísticas globales de organizaciones.
     */
    public function obtenerEstadisticas(): array
    {
        return $this->repositorio->obtenerEstadisticas();
    }

    /**
     * Busca y retorna una organización por su identificador.
     */
    public function obtenerPorId(int $id): Organization
    {
        $organizacion = $this->repositorio->buscarPorId($id);

        if (! $organizacion) {
            throw new InvalidArgumentException('La organización solicitada no existe.');
        }

        return $organizacion;
    }

    /**
     * Registra una nueva organización en el sistema.
     */
    public function registrarOrganizacion(array $datos): Organization
    {
        $datosLimpios = [
            'name' => trim($datos['name']),
            'address' => $datos['address'] ?? null,
            'phone' => $datos['phone'] ?? null,
            'phone_ext' => $datos['phone_ext'] ?? null,
            'website' => $datos['website'] ?? null,
            'notes' => $datos['notes'] ?? null,
            'plain_text_emails' => ! empty($datos['plain_text_emails']),
            'empresa_id' => auth('staff')->user()->empresa_id ?? 1,
        ];

        return $this->repositorio->crear($datosLimpios);
    }

    /**
     * Actualiza la información de una organización existente.
     */
    public function actualizarOrganizacion(int $id, array $datos): Organization
    {
        $organizacion = $this->obtenerPorId($id);
        $nombreAnterior = $organizacion->name;

        $datosActualizar = [
            'name' => trim($datos['name']),
            'address' => $datos['address'] ?? null,
            'phone' => $datos['phone'] ?? null,
            'phone_ext' => $datos['phone_ext'] ?? null,
            'website' => $datos['website'] ?? null,
            'notes' => $datos['notes'] ?? null,
            'plain_text_emails' => ! empty($datos['plain_text_emails']),
        ];

        $this->repositorio->actualizar($organizacion, $datosActualizar);

        // Si cambió el nombre, sincronizar company en los usuarios asociados
        if (strcasecmp($nombreAnterior, $datosActualizar['name']) !== 0) {
            User::where('company', $nombreAnterior)
                ->orWhere('organization_id', $organizacion->id)
                ->update(['company' => $datosActualizar['name']]);
        }

        return $organizacion->fresh();
    }

    /**
     * Elimina una organización del sistema.
     */
    public function eliminarOrganizacion(int $id): bool
    {
        $organizacion = $this->obtenerPorId($id);

        return $this->repositorio->eliminar($organizacion);
    }

    /**
     * Retorna los usuarios asociados a una organización.
     */
    public function obtenerUsuarios(Organization $organizacion, int $porPagina = 10): LengthAwarePaginator
    {
        return $this->repositorio->obtenerUsuariosPaginados($organizacion, $porPagina);
    }

    /**
     * Retorna los tickets asociados a una organización.
     */
    public function obtenerTickets(Organization $organizacion, int $porPagina = 10): LengthAwarePaginator
    {
        return $this->repositorio->obtenerTicketsPaginados($organizacion, $porPagina);
    }

    /**
     * Agrega un usuario existente a la organización.
     */
    public function agregarMiembro(int $organizacionId, int $usuarioId): bool
    {
        $organizacion = $this->obtenerPorId($organizacionId);

        return $this->repositorio->vincularUsuario($organizacion, $usuarioId);
    }

    /**
     * Remueve un usuario de la organización.
     */
    public function removerMiembro(int $organizacionId, int $usuarioId): bool
    {
        $organizacion = $this->obtenerPorId($organizacionId);

        return $this->repositorio->desvincularUsuario($organizacion, $usuarioId);
    }
}
