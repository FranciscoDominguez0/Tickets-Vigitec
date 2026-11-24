<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class AdministrarReferidosTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'staff_id' => ['nullable', 'integer', 'exists:staff,id'],
            'dept_id' => ['nullable', 'integer', 'exists:departments,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validador) {
            if (empty($this->staff_id) && empty($this->dept_id)) {
                $validador->errors()->add('referido', 'Debe seleccionar un agente o un departamento para referir el ticket.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'staff_id.exists' => 'El agente seleccionado no existe.',
            'dept_id.exists' => 'El departamento seleccionado no existe.',
        ];
    }
}
