<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EnviarNotificacionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación aplicables para enviar una notificación del sistema.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'destinatario_tipo' => ['required', 'string', 'in:todos_agentes,agente_especifico,todos_clientes,cliente_especifico'],
            'staff_id' => ['nullable', 'required_if:destinatario_tipo,agente_especifico', 'integer', 'exists:staff,id'],
            'user_id' => ['nullable', 'required_if:destinatario_tipo,cliente_especifico', 'integer', 'exists:users,id'],
            'type' => ['required', 'string', 'in:general,alerta,mantenimiento,recordatorio,sistema'],
            'message' => ['required', 'string', 'min:3', 'max:1000'],
            'related_id' => ['nullable', 'integer'],
        ];
    }

    /**
     * Mensajes personalizados para los errores de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'destinatario_tipo.required' => 'Debes seleccionar el tipo de destinatario.',
            'destinatario_tipo.in' => 'El tipo de destinatario seleccionado no es válido.',
            'staff_id.required_if' => 'Debes seleccionar el agente destinatario.',
            'staff_id.exists' => 'El agente seleccionado no existe en el sistema.',
            'user_id.required_if' => 'Debes seleccionar el cliente destinatario.',
            'user_id.exists' => 'El cliente seleccionado no existe en el sistema.',
            'type.required' => 'El tipo de notificación es obligatorio.',
            'type.in' => 'El tipo de notificación seleccionado no es válido.',
            'message.required' => 'El mensaje de la notificación es obligatorio.',
            'message.min' => 'El mensaje debe tener al menos 3 caracteres.',
            'message.max' => 'El mensaje no puede superar los 1000 caracteres.',
        ];
    }
}
