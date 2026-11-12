<?php

namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TicketRepository
{
    /**
     * Retorna tickets paginados con sus relaciones para el listado del panel.
     */
    public function obtenerPaginados(?string $filtro = null, int $porPagina = 20): LengthAwarePaginator
    {
        $consulta = Ticket::with(['user', 'department', 'priority', 'status', 'staff', 'thread.entries'])
            ->orderByDesc('created');

        if ($filtro === 'billing') {
            $consulta->where('status_id', 3)->whereDoesntHave('report');
        }

        return $consulta->paginate($porPagina);
    }

    /**
     * Busca un ticket con todas sus relaciones cargadas para la vista de detalle.
     */
    public function buscarConDetalles(int $id): ?Ticket
    {
        return Ticket::with([
            'user',
            'department',
            'priority',
            'status',
            'thread.entries.attachments',
            'staff',
        ])->find($id);
    }

    /**
     * Busca un ticket por su identificador primario.
     */
    public function buscarPorId(int $id): ?Ticket
    {
        return Ticket::find($id);
    }

    /**
     * Almacena un nuevo ticket en la base de datos.
     */
    public function crear(array $datos): Ticket
    {
        return Ticket::create($datos);
    }

    /**
     * Actualiza un ticket existente.
     */
    public function actualizar(Ticket $ticket, array $datos): bool
    {
        return $ticket->update($datos);
    }

    /**
     * Elimina un ticket de la base de datos.
     */
    public function eliminar(Ticket $ticket): bool
    {
        return (bool) $ticket->delete();
    }

    /**
     * Obtiene el resumen de métricas para la cabecera de tickets.
     */
    public function obtenerEstadisticas(int $staffId): array
    {
        return [
            'open' => Ticket::where('status_id', 1)->count(),
            'unassigned' => Ticket::where(function ($q) {
                $q->whereNull('staff_id')->orWhere('staff_id', 0);
            })->count(),
            'mine' => Ticket::where('staff_id', $staffId)->count(),
            'billing' => Ticket::where('status_id', 3)->whereDoesntHave('report')->count(),
        ];
    }

    /**
     * Obtiene los tickets paginados de un cliente con filtros aplicados.
     */
    public function obtenerPorUsuarioPaginados(
        int $usuarioId,
        int $empresaId,
        ?string $filtro = 'abiertos',
        ?string $busqueda = null,
        int $porPagina = 10
    ): LengthAwarePaginator {
        $consulta = Ticket::query()
            ->where('empresa_id', $empresaId)
            ->where('user_id', $usuarioId)
            ->with(['department', 'status', 'priority', 'helpTopic'])
            ->orderByDesc('created');

        if ($filtro === 'abiertos') {
            $consulta->whereNull('closed');
        } elseif ($filtro === 'cerrados') {
            $consulta->whereNotNull('closed');
        }

        if (! empty($busqueda)) {
            $consulta->where(function ($sub) use ($busqueda) {
                $sub->where('ticket_number', 'like', "%{$busqueda}%")
                    ->orWhere('subject', 'like', "%{$busqueda}%");
            });
        }

        return $consulta->paginate($porPagina);
    }

    /**
     * Obtiene el recuento de tickets abiertos y cerrados para un usuario.
     */
    public function obtenerEstadisticasUsuario(int $usuarioId, int $empresaId): array
    {
        $abiertos = Ticket::where('empresa_id', $empresaId)->where('user_id', $usuarioId)->whereNull('closed')->count();
        $cerrados = Ticket::where('empresa_id', $empresaId)->where('user_id', $usuarioId)->whereNotNull('closed')->count();

        return [
            'abiertos' => $abiertos,
            'cerrados' => $cerrados,
            'total' => $abiertos + $cerrados,
        ];
    }

    /**
     * Busca un ticket para un cliente específico garantizando control de acceso por empresa y usuario.
     */
    public function buscarParaUsuario(int $id, int $usuarioId, int $empresaId): ?Ticket
    {
        return Ticket::where('empresa_id', $empresaId)
            ->where('user_id', $usuarioId)
            ->with([
                'department',
                'status',
                'priority',
                'helpTopic',
                'staff',
                'thread.entries' => function ($consulta) {
                    $consulta->where('is_internal', 0)
                        ->with(['user', 'staff', 'attachments'])
                        ->orderBy('created', 'asc');
                },
            ])
            ->find($id);
    }
}
