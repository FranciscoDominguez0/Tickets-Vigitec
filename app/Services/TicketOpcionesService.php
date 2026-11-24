<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Banlist;
use App\Models\Department;
use App\Models\Requisition;
use App\Models\RequisitionItem;
use App\Models\Staff;
use App\Models\Thread;
use App\Models\ThreadEntry;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketCollaborator;
use App\Models\TicketLink;
use App\Models\TicketReferral;
use App\Models\TicketStatus;
use App\Models\User;
use Carbon\Carbon;
use DomainException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TicketOpcionesService
{
    /**
     * Registra una nota interna de auditoría dentro del hilo de un ticket.
     */
    public function registrarNotaInterna(Ticket $ticket, string $contenido, int $staffId): ThreadEntry
    {
        $hilo = $ticket->thread;

        if (! $hilo) {
            $hilo = Thread::create([
                'ticket_id' => $ticket->id,
                'empresa_id' => $ticket->empresa_id,
                'created' => now(),
            ]);
        }

        $entrada = $hilo->entries()->create([
            'empresa_id' => $ticket->empresa_id,
            'staff_id' => $staffId,
            'body' => $contenido,
            'is_internal' => 1,
            'created' => now(),
            'updated' => now(),
        ]);

        $ticket->update(['updated' => now()]);

        return $entrada;
    }

    /**
     * Cambia el usuario propietario de un ticket.
     */
    public function cambiarPropietario(Ticket $ticket, int $nuevoUsuarioId, int $staffId, ?string $motivo = null): Ticket
    {
        $nuevoUsuario = User::findOrFail($nuevoUsuarioId);
        $usuarioAnterior = $ticket->user;
        $nombreAnterior = $usuarioAnterior ? $usuarioAnterior->name : 'Sin asignar';

        $ticket->user_id = $nuevoUsuario->id;
        $ticket->save();

        $mensaje = "Propietario del ticket cambiado de \"{$nombreAnterior}\" a \"{$nuevoUsuario->name}\".";
        if (! empty($motivo)) {
            $mensaje .= " Motivo: {$motivo}";
        }

        $this->registrarNotaInterna($ticket, $mensaje, $staffId);

        return $ticket;
    }

    /**
     * Fusiona un ticket secundario dentro del ticket principal actual.
     */
    public function unirTickets(Ticket $ticketPrincipal, string|int $ticketSecundarioIdentificador, int $staffId, ?string $motivo = null): Ticket
    {
        $ticketSecundario = Ticket::where('empresa_id', $ticketPrincipal->empresa_id)
            ->where(function ($consulta) use ($ticketSecundarioIdentificador) {
                $consulta->where('id', $ticketSecundarioIdentificador)
                    ->orWhere('ticket_number', $ticketSecundarioIdentificador);
            })->first();

        if (! $ticketSecundario) {
            throw new InvalidArgumentException('El ticket secundario a fusionar no fue encontrado en esta empresa.');
        }

        if ($ticketSecundario->id === $ticketPrincipal->id) {
            throw new InvalidArgumentException('No es posible fusionar un ticket consigo mismo.');
        }

        DB::transaction(function () use ($ticketPrincipal, $ticketSecundario, $staffId, $motivo) {
            // Registrar el vínculo si no existe
            TicketLink::firstOrCreate([
                'ticket_id' => $ticketPrincipal->id,
                'linked_ticket_id' => $ticketSecundario->id,
            ], [
                'empresa_id' => $ticketPrincipal->empresa_id,
                'created' => now(),
            ]);

            // Cerrar el ticket secundario
            $estadoCerrado = TicketStatus::where('name', 'Cerrado')->first();
            $idCerrado = $estadoCerrado ? (int) $estadoCerrado->id : 5;

            $ticketSecundario->status_id = $idCerrado;
            $ticketSecundario->closed = now();
            $ticketSecundario->closed_at = now();
            $ticketSecundario->save();

            $mensajeSecundario = "Este ticket fue fusionado en el ticket #{$ticketPrincipal->ticket_number}.";
            if (! empty($motivo)) {
                $mensajeSecundario .= " Motivo: {$motivo}";
            }
            $this->registrarNotaInterna($ticketSecundario, $mensajeSecundario, $staffId);

            $mensajePrincipal = "El ticket #{$ticketSecundario->ticket_number} fue fusionado dentro de este ticket.";
            if (! empty($motivo)) {
                $mensajePrincipal .= " Motivo: {$motivo}";
            }
            $this->registrarNotaInterna($ticketPrincipal, $mensajePrincipal, $staffId);
        });

        return $ticketPrincipal;
    }

    /**
     * Vincula un ticket relacionado con el ticket actual.
     */
    public function vincularTicket(Ticket $ticket, string|int $ticketIdentificador, int $staffId): TicketLink
    {
        $ticketAVincular = Ticket::where('empresa_id', $ticket->empresa_id)
            ->where(function ($consulta) use ($ticketIdentificador) {
                $consulta->where('id', $ticketIdentificador)
                    ->orWhere('ticket_number', $ticketIdentificador);
            })->first();

        if (! $ticketAVincular) {
            throw new InvalidArgumentException('El ticket a vincular no fue encontrado.');
        }

        if ($ticketAVincular->id === $ticket->id) {
            throw new InvalidArgumentException('No se puede vincular un ticket consigo mismo.');
        }

        $vinculoExistente = TicketLink::where(function ($consulta) use ($ticket, $ticketAVincular) {
            $consulta->where('ticket_id', $ticket->id)->where('linked_ticket_id', $ticketAVincular->id);
        })->orWhere(function ($consulta) use ($ticket, $ticketAVincular) {
            $consulta->where('ticket_id', $ticketAVincular->id)->where('linked_ticket_id', $ticket->id);
        })->first();

        if ($vinculoExistente) {
            throw new DomainException('Estos tickets ya se encuentran vinculados.');
        }

        $vinculo = TicketLink::create([
            'ticket_id' => $ticket->id,
            'linked_ticket_id' => $ticketAVincular->id,
            'empresa_id' => $ticket->empresa_id,
            'created' => now(),
        ]);

        $this->registrarNotaInterna($ticket, "Ticket #{$ticketAVincular->ticket_number} vinculado a este ticket.", $staffId);

        return $vinculo;
    }

    /**
     * Elimina el vínculo entre dos tickets.
     */
    public function desvincularTicket(Ticket $ticket, int $enlaceId, int $staffId): void
    {
        $vinculo = TicketLink::where('id', $enlaceId)
            ->where(function ($consulta) use ($ticket) {
                $consulta->where('ticket_id', $ticket->id)
                    ->orWhere('linked_ticket_id', $ticket->id);
            })->firstOrFail();

        $otroTicketId = ($vinculo->ticket_id === $ticket->id) ? $vinculo->linked_ticket_id : $vinculo->ticket_id;
        $otroTicket = Ticket::find($otroTicketId);
        $numeroOtro = $otroTicket ? $otroTicket->ticket_number : (string) $otroTicketId;

        $vinculo->delete();

        $this->registrarNotaInterna($ticket, "Vínculo con el ticket #{$numeroOtro} eliminado.", $staffId);
    }

    /**
     * Modifica las horas de soporte registradas para el ticket.
     */
    public function actualizarHorasSoporte(Ticket $ticket, ?string $inicio, ?string $fin, int $staffId): Ticket
    {
        $ticket->support_start = ! empty($inicio) ? Carbon::parse($inicio) : null;
        $ticket->support_end = ! empty($fin) ? Carbon::parse($fin) : null;
        $ticket->save();

        $textoInicio = $ticket->support_start ? $ticket->support_start->format('d/m/Y H:i') : 'Sin definir';
        $textoFin = $ticket->support_end ? $ticket->support_end->format('d/m/Y H:i') : 'Sin definir';

        $this->registrarNotaInterna(
            $ticket,
            "Horas de soporte actualizadas: Inicio: {$textoInicio} | Fin: {$textoFin}.",
            $staffId
        );

        return $ticket;
    }

    /**
     * Añade una referencia de departamento o personal al ticket.
     */
    public function agregarReferido(Ticket $ticket, ?int $staffIdReferido, ?int $deptIdReferido, int $staffId): TicketReferral
    {
        $referenciaExistente = TicketReferral::where('ticket_id', $ticket->id)
            ->where('staff_id', $staffIdReferido)
            ->where('dept_id', $deptIdReferido)
            ->first();

        if ($referenciaExistente) {
            throw new DomainException('Este ticket ya se encuentra referido al destino seleccionado.');
        }

        $referido = TicketReferral::create([
            'ticket_id' => $ticket->id,
            'staff_id' => $staffIdReferido,
            'dept_id' => $deptIdReferido,
            'created' => now(),
        ]);

        $destinatario = 'desconocido';
        if ($staffIdReferido) {
            $agente = Staff::find($staffIdReferido);
            $destinatario = $agente ? "Agente {$agente->firstname} {$agente->lastname}" : "Agente #{$staffIdReferido}";
        } elseif ($deptIdReferido) {
            $departamento = Department::find($deptIdReferido);
            $destinatario = $departamento ? "Departamento {$departamento->name}" : "Departamento #{$deptIdReferido}";
        }

        $this->registrarNotaInterna($ticket, "Ticket referido a: {$destinatario}.", $staffId);

        return $referido;
    }

    /**
     * Elimina una referencia del ticket.
     */
    public function eliminarReferido(Ticket $ticket, int $referidoId, int $staffId): void
    {
        $referido = TicketReferral::where('id', $referidoId)
            ->where('ticket_id', $ticket->id)
            ->firstOrFail();

        $referido->delete();

        $this->registrarNotaInterna($ticket, 'Se eliminó una referencia asignada al ticket.', $staffId);
    }

    /**
     * Genera una nueva solicitud de requisición de inventario con sus artículos.
     */
    public function crearRequisicion(Ticket $ticket, array $items, int $staffId): Requisition
    {
        return DB::transaction(function () use ($ticket, $items, $staffId) {
            $cliente = $ticket->user;
            $nombreCliente = $cliente ? $cliente->name : ($ticket->walkin_phone ?? 'Cliente');

            $requisicion = Requisition::create([
                'ticket_id' => $ticket->id,
                'empresa_id' => $ticket->empresa_id,
                'agent_id' => $staffId,
                'client_id' => $ticket->user_id,
                'client_name' => $nombreCliente,
                'status' => 'pending',
                'created_at' => now(),
            ]);

            foreach ($items as $item) {
                if (! empty($item['product_name']) && ! empty($item['quantity'])) {
                    RequisitionItem::create([
                        'requisition_id' => $requisicion->id,
                        'product_name' => trim((string) $item['product_name']),
                        'quantity' => (int) $item['quantity'],
                        'quantity_used' => 0,
                    ]);
                }
            }

            $cantidadArticulos = count($items);
            $this->registrarNotaInterna(
                $ticket,
                "Solicitud de requisición de inventario #{$requisicion->id} registrada ({$cantidadArticulos} artículos solicitados).",
                $staffId
            );

            return $requisicion;
        });
    }

    /**
     * Añade un colaborador al ticket verificando los límites configurados.
     */
    public function agregarColaborador(Ticket $ticket, string $email, ?string $nombre, ?int $usuarioId, int $staffId): TicketCollaborator
    {
        $limiteMaximo = (int) AppSetting::obtener('users.max_collaborators_per_ticket', 10, $ticket->empresa_id);
        $totalColaboradores = $ticket->colaboradores()->count();

        if ($totalColaboradores >= $limiteMaximo) {
            throw new DomainException("Se ha alcanzado el límite máximo permitido de {$limiteMaximo} colaboradores para este ticket.");
        }

        $emailLimpio = strtolower(trim($email));

        $colaboradorExistente = TicketCollaborator::where('ticket_id', $ticket->id)
            ->where('email', $emailLimpio)
            ->first();

        if ($colaboradorExistente) {
            throw new DomainException('Este correo ya está registrado como colaborador en el ticket.');
        }

        if (! $usuarioId) {
            $usuarioEncontrado = User::where('empresa_id', $ticket->empresa_id)
                ->where('email', $emailLimpio)
                ->first();

            if ($usuarioEncontrado) {
                $usuarioId = $usuarioEncontrado->id;
                $nombre = $nombre ?: $usuarioEncontrado->name;
            }
        }

        $colaborador = TicketCollaborator::create([
            'ticket_id' => $ticket->id,
            'empresa_id' => $ticket->empresa_id,
            'user_id' => $usuarioId,
            'name' => $nombre ?: $emailLimpio,
            'email' => $emailLimpio,
            'role' => 'colaborador',
        ]);

        $nombreMostrar = $colaborador->name ?: $emailLimpio;
        $this->registrarNotaInterna($ticket, "Colaborador agregado: {$nombreMostrar} ({$emailLimpio}).", $staffId);

        return $colaborador;
    }

    /**
     * Elimina un colaborador asignado al ticket.
     */
    public function eliminarColaborador(Ticket $ticket, int $colaboradorId, int $staffId): void
    {
        $colaborador = TicketCollaborator::where('id', $colaboradorId)
            ->where('ticket_id', $ticket->id)
            ->firstOrFail();

        $nombreMostrar = $colaborador->name ?: $colaborador->email;
        $colaborador->delete();

        $this->registrarNotaInterna($ticket, "Colaborador removido: {$nombreMostrar}.", $staffId);
    }

    /**
     * Crea una solicitud de revisión ejecutiva asignada a un gerente o supervisor.
     */
    public function solicitarRevisionEjecutiva(Ticket $ticket, int $gerenteId, string $motivo, int $staffId): TicketApproval
    {
        $gerente = Staff::findOrFail($gerenteId);

        $aprobacion = TicketApproval::create([
            'ticket_id' => $ticket->id,
            'requested_by_staff_id' => $staffId,
            'manager_id' => $gerente->id,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        $nombreGerente = trim("{$gerente->firstname} {$gerente->lastname}");
        $this->registrarNotaInterna(
            $ticket,
            "Revisión ejecutiva solicitada a {$nombreGerente}. Motivo: {$motivo}",
            $staffId
        );

        return $aprobacion;
    }

    /**
     * Bloquea el correo electrónico o dominio del cliente del ticket en la lista negra.
     */
    public function bloquearEmail(Ticket $ticket, int $staffId, ?string $motivo = null, bool $bloquearDominio = false): Banlist
    {
        $correo = $ticket->user ? $ticket->user->email : null;

        if (empty($correo)) {
            throw new InvalidArgumentException('El ticket no cuenta con un correo electrónico asociado para bloquear.');
        }

        $dominio = substr(strrchr($correo, '@'), 1);

        $existente = Banlist::where('empresa_id', $ticket->empresa_id)
            ->where(function ($consulta) use ($correo, $dominio, $bloquearDominio) {
                if ($bloquearDominio) {
                    $consulta->where('domain', $dominio);
                } else {
                    $consulta->where('email', $correo);
                }
            })->first();

        if ($existente) {
            throw new DomainException('El destinatario o dominio ya se encuentra en la lista de bloqueo.');
        }

        $bloqueo = Banlist::create([
            'empresa_id' => $ticket->empresa_id,
            'email' => $bloquearDominio ? null : $correo,
            'domain' => $bloquearDominio ? $dominio : null,
            'notes' => $motivo ?: "Bloqueado desde opciones del ticket #{$ticket->ticket_number}",
            'is_active' => true,
            'created' => now(),
            'updated' => now(),
        ]);

        $identificador = $bloquearDominio ? "@{$dominio}" : $correo;
        $this->registrarNotaInterna(
            $ticket,
            "El identificador {$identificador} fue ingresado a la lista de bloqueo.",
            $staffId
        );

        return $bloqueo;
    }
}
