<?php

namespace App\DataTransferObjects\Configuracion;

use App\Http\Requests\Admin\ActualizarConfiguracionAgentesRequest;

class ConfiguracionAgentesData
{
    public function __construct(
        public string $formatoNombre = 'first_last',
        public bool $ocultarIdentidad = false,
        public string $origenAvatar = 'initials',
        public bool $deshabilitarColaboradores = false,
        public int $diasExpiracionClave = 0,
        public bool $permitirRestablecerClave = true,
        public int $vigenciaTokenClave = 30,
        public int $longitudMinimaClave = 8,
        public bool $requerirSimbolosClave = false,
        public int $tiempoExpiracionSesion = 120,
        public int $intentosMaximosLogin = 5,
        public int $duracionBloqueo = 30,
        public bool $vincularIpSesion = false,
    ) {}

    /**
     * Construye el DTO desde la solicitud HTTP validada.
     */
    public static function desdeSolicitud(ActualizarConfiguracionAgentesRequest $solicitud): self
    {
        return new self(
            formatoNombre: (string) $solicitud->validated('staff_name_format'),
            ocultarIdentidad: $solicitud->boolean('hide_staff_name'),
            origenAvatar: (string) $solicitud->validated('agent_avatar_source'),
            deshabilitarColaboradores: $solicitud->boolean('disable_agent_collaborators'),
            diasExpiracionClave: (int) $solicitud->validated('staff_password_expiration_days'),
            permitirRestablecerClave: $solicitud->boolean('staff_allow_pw_reset'),
            vigenciaTokenClave: (int) $solicitud->validated('staff_pw_reset_window'),
            longitudMinimaClave: (int) $solicitud->validated('staff_password_min_length'),
            requerirSimbolosClave: $solicitud->boolean('staff_password_require_symbols'),
            tiempoExpiracionSesion: (int) $solicitud->validated('staff_session_timeout'),
            intentosMaximosLogin: (int) $solicitud->validated('staff_max_login_attempts'),
            duracionBloqueo: (int) $solicitud->validated('staff_lockout_duration'),
            vincularIpSesion: $solicitud->boolean('staff_bind_agent_ip'),
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
            formatoNombre: (string) ($valores['agents.name_format'] ?? 'first_last'),
            ocultarIdentidad: filter_var($valores['agents.hide_staff_name'] ?? false, FILTER_VALIDATE_BOOLEAN),
            origenAvatar: (string) ($valores['agents.avatar_source'] ?? 'initials'),
            deshabilitarColaboradores: filter_var($valores['agents.disable_agent_collaborators'] ?? false, FILTER_VALIDATE_BOOLEAN),
            diasExpiracionClave: (int) ($valores['agents.password_expiration_days'] ?? 0),
            permitirRestablecerClave: filter_var($valores['agents.allow_pw_reset'] ?? true, FILTER_VALIDATE_BOOLEAN),
            vigenciaTokenClave: (int) ($valores['agents.pw_reset_window'] ?? 30),
            longitudMinimaClave: (int) ($valores['agents.password_min_length'] ?? 8),
            requerirSimbolosClave: filter_var($valores['agents.password_require_symbols'] ?? false, FILTER_VALIDATE_BOOLEAN),
            tiempoExpiracionSesion: (int) ($valores['agents.session_timeout'] ?? 120),
            intentosMaximosLogin: (int) ($valores['agents.max_login_attempts'] ?? 5),
            duracionBloqueo: (int) ($valores['agents.lockout_duration'] ?? 30),
            vincularIpSesion: filter_var($valores['agents.bind_agent_ip'] ?? false, FILTER_VALIDATE_BOOLEAN),
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
            'agents.name_format' => $this->formatoNombre,
            'agents.hide_staff_name' => $this->ocultarIdentidad ? '1' : '0',
            'agents.avatar_source' => $this->origenAvatar,
            'agents.disable_agent_collaborators' => $this->deshabilitarColaboradores ? '1' : '0',
            'agents.password_expiration_days' => (string) $this->diasExpiracionClave,
            'agents.allow_pw_reset' => $this->permitirRestablecerClave ? '1' : '0',
            'agents.pw_reset_window' => (string) $this->vigenciaTokenClave,
            'agents.password_min_length' => (string) $this->longitudMinimaClave,
            'agents.password_require_symbols' => $this->requerirSimbolosClave ? '1' : '0',
            'agents.session_timeout' => (string) $this->tiempoExpiracionSesion,
            'agents.max_login_attempts' => (string) $this->intentosMaximosLogin,
            'agents.lockout_duration' => (string) $this->duracionBloqueo,
            'agents.bind_agent_ip' => $this->vincularIpSesion ? '1' : '0',
        ];
    }
}
