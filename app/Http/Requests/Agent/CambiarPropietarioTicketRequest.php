<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class CambiarPropietarioTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'motivo' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Debe seleccionar un cliente nuevo.',
            'user_id.exists' => 'El cliente seleccionado no existe en el sistema.',
            'motivo.max' => 'El motivo no puede exceder los 500 caracteres.',
        ];
    }
}
