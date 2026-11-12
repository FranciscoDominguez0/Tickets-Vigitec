<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarConfiguracionSolicitudesRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado tiene permisos de administrador.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para los ajustes de solicitudes y numeración de tickets.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ticket_number_format' => ['required', 'string', 'max:50'],
            'ticket_sequence_id' => ['required', 'string'],
            'default_ticket_status_id' => ['nullable', 'integer'],
            'default_priority_id' => ['nullable', 'integer'],
            'default_help_topic' => ['nullable', 'integer'],
            'max_open_tickets' => ['nullable', 'integer', 'between:0,999'],
            'block_new_if_signature_pending' => ['nullable'],
            'ticket_max_file_mb' => ['required', 'integer', 'between:1,256'],
            'ticket_max_uploads' => ['required', 'integer', 'between:0,20'],
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
            'ticket_number_format.required' => 'El formato del número de ticket es obligatorio.',
            'ticket_sequence_id.required' => 'Debes indicar el método de generación o secuencia para los tickets.',
            'ticket_max_file_mb.between' => 'El tamaño máximo por archivo debe estar entre 1 y 256 MB.',
            'ticket_max_uploads.between' => 'El número máximo de subidas debe estar entre 0 y 20.',
            'max_open_tickets.between' => 'El máximo de tickets abiertos debe estar entre 0 y 999.',
        ];
    }
}
