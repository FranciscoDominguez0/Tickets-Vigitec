<?php

namespace App\Services;

use App\Mail\EstadoTicketActualizadoMail;
use App\Mail\TicketCerradoConFirmaMail;
use App\Mail\TicketCreadoMail;
use App\Models\EmailAccount;
use App\Models\Staff;
use App\Models\Ticket;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class TicketNotificacionService
{
    public function __construct(
        protected TicketPdfService $ticketPdfService
    ) {}

    /**
     * Envía las notificaciones correspondientes tras la creación de un nuevo ticket.
     */
    public function notificarCreacion(Ticket $ticket, ?string $cuerpoInicial = null): void
    {
        try {
            $ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff']);
            $empresaId = (int) ($ticket->empresa_id ?? 1);
            $cuentaEmisora = $this->obtenerCuentaEmisora($empresaId, $ticket->dept_id);

            // 1. Notificar a los agentes (asignado o del departamento)
            $correosAgentes = $this->obtenerCorreosAgentes($ticket);
            foreach ($correosAgentes as $correoAgente) {
                $mailableAgente = new TicketCreadoMail($ticket, $cuerpoInicial, esParaAgente: true);
                $this->enviarCorreo($correoAgente, $mailableAgente, $cuentaEmisora);
            }

            // 2. Notificar al cliente solicitante
            if ($ticket->user && ! empty($ticket->user->email)) {
                $mailableCliente = new TicketCreadoMail($ticket, $cuerpoInicial, esParaAgente: false);
                $this->enviarCorreo($ticket->user->email, $mailableCliente, $cuentaEmisora);
            }
        } catch (Throwable $e) {
            Log::warning('Error al enviar notificaciones de creación de ticket #'.$ticket->ticket_number.': '.$e->getMessage(), [
                'ticket_id' => $ticket->id,
            ]);
        }
    }

    /**
     * Envía notificaciones cuando un ticket cambia de estado.
     */
    public function notificarCambioEstado(
        Ticket $ticket,
        string $estadoAnterior,
        string $estadoNuevo,
        ?string $nombreResponsable = null
    ): void {
        try {
            $ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff']);
            $empresaId = (int) ($ticket->empresa_id ?? 1);
            $cuentaEmisora = $this->obtenerCuentaEmisora($empresaId, $ticket->dept_id);

            // 1. Notificar a los agentes
            $correosAgentes = $this->obtenerCorreosAgentes($ticket);
            foreach ($correosAgentes as $correoAgente) {
                $mailable = new EstadoTicketActualizadoMail(
                    ticket: $ticket,
                    estadoAnterior: $estadoAnterior,
                    estadoNuevo: $estadoNuevo,
                    nombreResponsable: $nombreResponsable,
                    esParaAgente: true
                );
                $this->enviarCorreo($correoAgente, $mailable, $cuentaEmisora);
            }

            // 2. Notificar al cliente
            if ($ticket->user && ! empty($ticket->user->email)) {
                $mailableCliente = new EstadoTicketActualizadoMail(
                    ticket: $ticket,
                    estadoAnterior: $estadoAnterior,
                    estadoNuevo: $estadoNuevo,
                    nombreResponsable: $nombreResponsable,
                    esParaAgente: false
                );
                $this->enviarCorreo($ticket->user->email, $mailableCliente, $cuentaEmisora);
            }
        } catch (Throwable $e) {
            Log::warning('Error al enviar notificación de cambio de estado de ticket #'.$ticket->ticket_number.': '.$e->getMessage(), [
                'ticket_id' => $ticket->id,
            ]);
        }
    }

    /**
     * Envía notificación con acta en PDF adjunta tras cerrarse el ticket con firma digital.
     */
    public function notificarCierreConFirma(Ticket $ticket, ?string $notasCierre = null): void
    {
        try {
            $ticket->loadMissing(['user', 'department', 'priority', 'status', 'staff', 'report.items']);
            $empresaId = (int) ($ticket->empresa_id ?? 1);
            $cuentaEmisora = $this->obtenerCuentaEmisora($empresaId, $ticket->dept_id);

            // 1. Notificar a los agentes con PDF y firma adjunta
            $correosAgentes = $this->obtenerCorreosAgentes($ticket);
            foreach ($correosAgentes as $correoAgente) {
                $mailableAgente = new TicketCerradoConFirmaMail(
                    ticket: $ticket,
                    esParaAgente: true,
                    notasCierre: $notasCierre,
                    pdfService: $this->ticketPdfService
                );
                $this->enviarCorreo($correoAgente, $mailableAgente, $cuentaEmisora);
            }

            // 2. Notificar al cliente con PDF y firma adjunta
            if ($ticket->user && ! empty($ticket->user->email)) {
                $mailableCliente = new TicketCerradoConFirmaMail(
                    ticket: $ticket,
                    esParaAgente: false,
                    notasCierre: $notasCierre,
                    pdfService: $this->ticketPdfService
                );
                $this->enviarCorreo($ticket->user->email, $mailableCliente, $cuentaEmisora);
            }
        } catch (Throwable $e) {
            Log::warning('Error al enviar notificación de cierre con firma de ticket #'.$ticket->ticket_number.': '.$e->getMessage(), [
                'ticket_id' => $ticket->id,
            ]);
        }
    }

    /**
     * Obtiene la lista única de correos de los agentes asignados o miembros del departamento.
     *
     * @return array<int, string>
     */
    public function obtenerCorreosAgentes(Ticket $ticket): array
    {
        $correos = [];
        $empresaId = (int) ($ticket->empresa_id ?? 1);

        // Agente asignado individualmente
        if ($ticket->staff && ! empty($ticket->staff->email) && $ticket->staff->is_active) {
            $correos[] = trim($ticket->staff->email);
        }

        // Agentes del departamento
        if ($ticket->dept_id) {
            $agentesPrimarios = Staff::where('dept_id', $ticket->dept_id)
                ->where('empresa_id', $empresaId)
                ->where('is_active', 1)
                ->whereNotNull('email')
                ->pluck('email')
                ->toArray();

            $correos = array_merge($correos, $agentesPrimarios);

            $agentesSecundarios = DB::table('staff_departments')
                ->join('staff', 'staff.id', '=', 'staff_departments.staff_id')
                ->where('staff_departments.dept_id', $ticket->dept_id)
                ->where('staff.empresa_id', $empresaId)
                ->where('staff.is_active', 1)
                ->whereNotNull('staff.email')
                ->pluck('staff.email')
                ->toArray();

            $correos = array_merge($correos, $agentesSecundarios);
        }

        return array_values(array_unique(array_filter(array_map('trim', $correos))));
    }

    /**
     * Obtiene la cuenta de correo emisora configurada en la base de datos.
     */
    public function obtenerCuentaEmisora(int $empresaId, ?int $deptId = null): ?EmailAccount
    {
        if ($deptId) {
            $cuentaDept = EmailAccount::where('empresa_id', $empresaId)
                ->where('dept_id', $deptId)
                ->first();

            if ($cuentaDept) {
                return $cuentaDept;
            }
        }

        return EmailAccount::where('empresa_id', $empresaId)
            ->where('is_default', 1)
            ->first();
    }

    /**
     * Envía un correo con el remitente configurado.
     */
    protected function enviarCorreo(string $destinatario, Mailable $mailable, ?EmailAccount $cuentaEmisora = null): void
    {
        if (! filter_var($destinatario, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        if ($cuentaEmisora && ! empty($cuentaEmisora->email)) {
            $mailable->from($cuentaEmisora->email, $cuentaEmisora->name ?: config('mail.from.name'));
        }

        Mail::to($destinatario)->send($mailable);
    }
}
