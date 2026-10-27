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
}
