<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class SolicitarRevisionEjecutivaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'manager_id' => ['required', 'integer', 'exists:staff,id'],
            'motivo' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'manager_id.required' => 'Debe seleccionar al gerente o supervisor responsable.',
            'manager_id.exists' => 'El gerente seleccionado no existe.',
            'motivo.required' => 'Debe ingresar el motivo de la revisión ejecutiva.',
            'motivo.max' => 'El motivo no puede exceder los 1000 caracteres.',
        ];
    }
}
