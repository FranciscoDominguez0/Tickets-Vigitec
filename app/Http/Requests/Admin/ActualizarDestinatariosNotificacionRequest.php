<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarDestinatariosNotificacionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación aplicables para actualizar los destinatarios de alertas globales.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'staff_ids' => ['nullable', 'array'],
            'staff_ids.*' => ['integer', 'exists:staff,id'],
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
            'staff_ids.array' => 'El formato de los agentes seleccionados no es válido.',
            'staff_ids.*.exists' => 'Uno de los agentes seleccionados no existe en el sistema.',
        ];
    }
}
