<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarConfiguracionUsuariosRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado tiene permisos de administrador.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para los ajustes de usuarios y clientes.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_registration' => ['required', 'string', 'in:public,private,disabled'],
            'client_registration_required' => ['nullable'],
            'client_verify_email' => ['nullable'],
            'client_allow_pw_reset' => ['nullable'],
            'client_session_timeout' => ['required', 'integer', 'between:0,1440'],
            'client_max_open_tickets' => ['required', 'integer', 'between:0,999'],
            'auto_assign_organization' => ['nullable'],
            'org_members_see_all_tickets' => ['nullable'],
            'auto_add_collaborators' => ['nullable'],
            'max_collaborators_per_ticket' => ['required', 'integer', 'between:0,50'],
            'client_welcome_msg' => ['nullable', 'string', 'max:2000'],
            'client_guest_ticket_notice' => ['nullable', 'string', 'max:2000'],
            'active_tab' => ['nullable', 'string', 'in:general,organizations,notices'],
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
            'client_registration.required' => 'El modo de registro de usuarios es obligatorio.',
            'client_registration.in' => 'El modo de registro seleccionado no es válido.',
            'client_session_timeout.required' => 'El tiempo de expiración de sesión es obligatorio.',
            'client_session_timeout.integer' => 'El tiempo de expiración de sesión debe ser un número entero.',
            'client_session_timeout.between' => 'El tiempo de expiración de sesión debe estar entre 0 y 1440 minutos.',
            'client_max_open_tickets.required' => 'El límite de tickets abiertos es obligatorio.',
            'client_max_open_tickets.integer' => 'El límite de tickets abiertos debe ser un número entero.',
            'client_max_open_tickets.between' => 'El límite de tickets abiertos debe estar entre 0 y 999 (0 para ilimitado).',
            'max_collaborators_per_ticket.required' => 'El número máximo de colaboradores es obligatorio.',
            'max_collaborators_per_ticket.integer' => 'El número máximo de colaboradores debe ser un número entero.',
            'max_collaborators_per_ticket.between' => 'El número máximo de colaboradores debe estar entre 0 y 50.',
            'client_welcome_msg.max' => 'El mensaje de bienvenida no puede superar los 2000 caracteres.',
            'client_guest_ticket_notice.max' => 'El aviso para invitados no puede superar los 2000 caracteres.',
        ];
    }
}
