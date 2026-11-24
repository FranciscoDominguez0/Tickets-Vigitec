<?php

namespace App\DataTransferObjects\Configuracion;

use App\Http\Requests\Admin\ActualizarConfiguracionUsuariosRequest;

class ConfiguracionUsuariosData
{
    public function __construct(
        public string $modoRegistro = 'public',
        public bool $registroRequerido = false,
        public bool $verificarEmail = true,
        public bool $permitirRestablecerClave = true,
        public int $tiempoExpiracionSesion = 60,
        public int $maxTicketsAbiertos = 0,
        public bool $asignarOrganizacionAuto = true,
        public bool $miembrosVenTicketsOrg = true,
        public bool $autoAgregarColaboradores = true,
        public int $maxColaboradores = 10,
        public string $mensajeBienvenida = '',
        public string $avisoInvitados = '',
    ) {}

    /**
     * Construye el DTO desde la solicitud HTTP validada.
     */
    public static function desdeSolicitud(ActualizarConfiguracionUsuariosRequest $solicitud): self
    {
        return new self(
            modoRegistro: (string) $solicitud->validated('client_registration'),
            registroRequerido: $solicitud->boolean('client_registration_required'),
            verificarEmail: $solicitud->boolean('client_verify_email'),
            permitirRestablecerClave: $solicitud->boolean('client_allow_pw_reset'),
            tiempoExpiracionSesion: (int) $solicitud->validated('client_session_timeout'),
            maxTicketsAbiertos: (int) $solicitud->validated('client_max_open_tickets'),
            asignarOrganizacionAuto: $solicitud->boolean('auto_assign_organization'),
            miembrosVenTicketsOrg: $solicitud->boolean('org_members_see_all_tickets'),
            autoAgregarColaboradores: $solicitud->boolean('auto_add_collaborators'),
            maxColaboradores: (int) $solicitud->validated('max_collaborators_per_ticket'),
            mensajeBienvenida: trim((string) ($solicitud->validated('client_welcome_msg') ?? '')),
            avisoInvitados: trim((string) ($solicitud->validated('client_guest_ticket_notice') ?? '')),
        );
    }

    /**
     * Construye el DTO a partir de un mapa clave-valor recuperado de la base de datos.
     *
     * @param  array<string, mixed>  $valores
     */
    public static function desdeValores(array $valores): self
    {
        return new self(
            modoRegistro: (string) ($valores['users.client_registration'] ?? 'public'),
            registroRequerido: filter_var($valores['users.client_registration_required'] ?? false, FILTER_VALIDATE_BOOLEAN),
            verificarEmail: filter_var($valores['users.client_verify_email'] ?? true, FILTER_VALIDATE_BOOLEAN),
            permitirRestablecerClave: filter_var($valores['users.client_allow_pw_reset'] ?? true, FILTER_VALIDATE_BOOLEAN),
            tiempoExpiracionSesion: (int) ($valores['users.client_session_timeout'] ?? 60),
            maxTicketsAbiertos: (int) ($valores['users.client_max_open_tickets'] ?? 0),
            asignarOrganizacionAuto: filter_var($valores['users.auto_assign_organization'] ?? true, FILTER_VALIDATE_BOOLEAN),
            miembrosVenTicketsOrg: filter_var($valores['users.org_members_see_all_tickets'] ?? true, FILTER_VALIDATE_BOOLEAN),
            autoAgregarColaboradores: filter_var($valores['users.auto_add_collaborators'] ?? true, FILTER_VALIDATE_BOOLEAN),
            maxColaboradores: (int) ($valores['users.max_collaborators_per_ticket'] ?? 10),
            mensajeBienvenida: (string) ($valores['users.client_welcome_msg'] ?? ''),
            avisoInvitados: (string) ($valores['users.client_guest_ticket_notice'] ?? ''),
        );
    }

    /**
     * Convierte el DTO a un arreglo asociativo de pares clave-valor para almacenamiento.
     *
     * @return array<string, string>
     */
    public function aParesClaveValor(): array
    {
        return [
            'users.client_registration' => $this->modoRegistro,
            'users.client_registration_required' => $this->registroRequerido ? '1' : '0',
            'users.client_verify_email' => $this->verificarEmail ? '1' : '0',
            'users.client_allow_pw_reset' => $this->permitirRestablecerClave ? '1' : '0',
            'users.client_session_timeout' => (string) $this->tiempoExpiracionSesion,
            'users.client_max_open_tickets' => (string) $this->maxTicketsAbiertos,
            'users.auto_assign_organization' => $this->asignarOrganizacionAuto ? '1' : '0',
            'users.org_members_see_all_tickets' => $this->miembrosVenTicketsOrg ? '1' : '0',
            'users.auto_add_collaboradores' => $this->autoAgregarColaboradores ? '1' : '0',
            'users.auto_add_collaborators' => $this->autoAgregarColaboradores ? '1' : '0',
            'users.max_collaborators_per_ticket' => (string) $this->maxColaboradores,
            'users.client_welcome_msg' => $this->mensajeBienvenida,
            'users.client_guest_ticket_notice' => $this->avisoInvitados,
        ];
    }
}
