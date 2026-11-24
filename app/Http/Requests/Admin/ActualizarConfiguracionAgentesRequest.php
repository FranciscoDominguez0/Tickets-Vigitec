<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarConfiguracionAgentesRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado tiene permisos de administrador.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para los ajustes de agentes y personal.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'staff_name_format' => ['required', 'string', 'in:first_last,last_first,username'],
            'hide_staff_name' => ['nullable'],
            'agent_avatar_source' => ['required', 'string', 'in:initials,gravatar,disabled'],
            'disable_agent_collaborators' => ['nullable'],
            'staff_password_expiration_days' => ['required', 'integer', 'between:0,365'],
            'staff_allow_pw_reset' => ['nullable'],
            'staff_pw_reset_window' => ['required', 'integer', 'between:5,1440'],
            'staff_password_min_length' => ['required', 'integer', 'between:6,32'],
            'staff_password_require_symbols' => ['nullable'],
            'staff_session_timeout' => ['required', 'integer', 'between:0,1440'],
            'staff_max_login_attempts' => ['required', 'integer', 'between:1,20'],
            'staff_lockout_duration' => ['required', 'integer', 'between:1,1440'],
            'staff_bind_agent_ip' => ['nullable'],
            'active_tab' => ['nullable', 'string', 'in:general,passwords,security'],
        ];
    }

    /**
     * Mensajes de error en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'staff_name_format.required' => 'El formato del nombre de agente es obligatorio.',
            'staff_name_format.in' => 'El formato de nombre seleccionado no es válido.',
            'agent_avatar_source.required' => 'El origen de los avatares es obligatorio.',
            'agent_avatar_source.in' => 'El origen de avatar seleccionado no es válido.',
            'staff_password_expiration_days.required' => 'Los días de expiración de contraseña son obligatorios.',
            'staff_password_expiration_days.between' => 'Los días de expiración deben estar entre 0 y 365 días (0 para no expirar).',
            'staff_pw_reset_window.required' => 'El tiempo de validez del enlace de restablecimiento es obligatorio.',
            'staff_pw_reset_window.between' => 'El tiempo de validez del enlace debe estar entre 5 y 1440 minutos.',
            'staff_password_min_length.required' => 'La longitud mínima de contraseña es obligatoria.',
            'staff_password_min_length.between' => 'La longitud mínima de contraseña debe estar entre 6 y 32 caracteres.',
            'staff_session_timeout.required' => 'El tiempo de expiración de sesión es obligatorio.',
            'staff_session_timeout.between' => 'El tiempo de expiración de sesión debe estar entre 0 y 1440 minutos.',
            'staff_max_login_attempts.required' => 'El número máximo de intentos fallidos es obligatorio.',
            'staff_max_login_attempts.between' => 'Los intentos fallidos permitidos deben estar entre 1 y 20.',
            'staff_lockout_duration.required' => 'La duración del bloqueo es obligatoria.',
            'staff_lockout_duration.between' => 'La duración del bloqueo debe estar entre 1 y 1440 minutos.',
        ];
    }
}
