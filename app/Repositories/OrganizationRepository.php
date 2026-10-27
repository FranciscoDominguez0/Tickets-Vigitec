<?php

namespace App\Repositories;

use App\Models\Organization;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrganizationRepository
{
    /**
     * Retorna todas las organizaciones paginadas con filtros y métricas calculadas.
     */
    public function obtenerFiltradas(?string $busqueda = null, int $porPagina = 12): LengthAwarePaginator
    {
        $consulta = Organization::query();

        if (! empty($busqueda)) {
            $termino = trim($busqueda);
            $consulta->where(function ($q) use ($termino) {
                $q->where('name', 'like', "%{$termino}%")
                    ->orWhere('phone', 'like', "%{$termino}%")
                    ->orWhere('website', 'like', "%{$termino}%")
                    ->orWhere('address', 'like', "%{$termino}%");
            });
        }

        $paginador = $consulta->orderBy('name')->paginate($porPagina);

        // Enriquecer cada organización con el conteo de usuarios y tickets asociados
        $paginador->getCollection()->transform(function (Organization $org) {
            $usuarioIds = DB::table('user_organizations')
                ->where('organization_id', $org->id)
                ->pluck('user_id')
                ->merge(User::where('company', $org->name)->pluck('id'))
                ->unique();

            $org->setAttribute('total_usuarios', $usuarioIds->count());

            $ticketsConsulta = Ticket::whereIn('user_id', $usuarioIds);
            $org->setAttribute('total_tickets', (clone $ticketsConsulta)->count());
            $org->setAttribute('tickets_abiertos', (clone $ticketsConsulta)->whereIn('status_id', [1, 2, 3])->count());

            return $org;
        });

        return $paginador;
    }

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
     * Busca una organización por su nombre exacto.
     */
    public function buscarPorNombre(string $nombre): ?Organization
    {
        return Organization::where('name', trim($nombre))->first();
    }

    /**
     * Registra una nueva organización en la base de datos.
     */
    public function crear(array $datos): Organization
    {
        return Organization::create($datos);
    }

    /**
     * Actualiza la información de una organización existente.
     */
    public function actualizar(Organization $organizacion, array $datos): bool
    {
        return $organizacion->update($datos);
    }

    /**
     * Elimina una organización y sus membresías asociadas.
     */
    public function eliminar(Organization $organizacion): bool
    {
        DB::table('user_organizations')->where('organization_id', $organizacion->id)->delete();

        return (bool) $organizacion->delete();
    }

    /**
     * Retorna estadísticas globales de organizaciones.
     */
    public function obtenerEstadisticas(): array
    {
        $total = Organization::count();
        $totalMiembros = DB::table('user_organizations')->distinct('user_id')->count('user_id');

        $miembrosIds = DB::table('user_organizations')->pluck('user_id');
        $ticketsAsociados = Ticket::whereIn('user_id', $miembrosIds)->count();
        $ticketsAbiertos = Ticket::whereIn('user_id', $miembrosIds)->whereIn('status_id', [1, 2, 3])->count();

        return [
            'total' => $total,
            'miembros' => $totalMiembros,
            'tickets' => $ticketsAsociados,
            'tickets_abiertos' => $ticketsAbiertos,
        ];
    }

    /**
     * Retorna los usuarios vinculados a la organización paginados.
     */
    public function obtenerUsuariosPaginados(Organization $organizacion, int $porPagina = 10): LengthAwarePaginator
    {
        $usuarioIds = DB::table('user_organizations')
            ->where('organization_id', $organizacion->id)
            ->pluck('user_id')
            ->merge(User::where('company', $organizacion->name)->pluck('id'))
            ->unique();

        return User::whereIn('id', $usuarioIds)
            ->withCount('tickets')
            ->orderBy('firstname')
            ->paginate($porPagina, ['*'], 'up');
    }

    /**
     * Retorna los tickets generados por miembros de la organización paginados.
     */
    public function obtenerTicketsPaginados(Organization $organizacion, int $porPagina = 10): LengthAwarePaginator
    {
        $usuarioIds = DB::table('user_organizations')
            ->where('organization_id', $organizacion->id)
            ->pluck('user_id')
            ->merge(User::where('company', $organizacion->name)->pluck('id'))
            ->unique();

        return Ticket::whereIn('user_id', $usuarioIds)
            ->with(['user', 'department', 'priority', 'status'])
            ->orderByDesc('id')
            ->paginate($porPagina, ['*'], 'tp');
    }

    /**
     * Vincula un usuario como miembro de la organización.
     */
    public function vincularUsuario(Organization $organizacion, int $usuarioId): bool
    {
        $usuario = User::find($usuarioId);
        if (! $usuario) {
            return false;
        }

        DB::table('user_organizations')->updateOrInsert(
            [
                'organization_id' => $organizacion->id,
                'user_id' => $usuarioId,
            ],
            [
                'empresa_id' => $organizacion->empresa_id ?? 1,
                'created_at' => now(),
            ]
        );

        $usuario->update([
            'organization_id' => $organizacion->id,
            'company' => $organizacion->name,
        ]);

        return true;
    }

    /**
     * Desvincula a un usuario de la organización.
     */
    public function desvincularUsuario(Organization $organizacion, int $usuarioId): bool
    {
        DB::table('user_organizations')
            ->where('organization_id', $organizacion->id)
            ->where('user_id', $usuarioId)
            ->delete();

        $usuario = User::find($usuarioId);
        if ($usuario && $usuario->organization_id === $organizacion->id) {
            $usuario->update(['organization_id' => null]);
        }

        return true;
    }
}
