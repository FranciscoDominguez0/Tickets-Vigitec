<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'dept_id' => ['required', 'integer', 'exists:departments,id'],
            'priority_id' => ['required', 'integer', 'exists:priorities,id'],
            'body' => ['nullable', 'string'],
            'walkin_phone' => ['nullable', 'string', 'max:20'],
            'walkin_address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Debes seleccionar un cliente.',
            'user_id.exists' => 'El cliente seleccionado no existe.',
            'subject.required' => 'El asunto del ticket es obligatorio.',
            'dept_id.required' => 'Debes asignar un departamento.',
            'dept_id.exists' => 'El departamento seleccionado no es válido.',
            'priority_id.required' => 'Debes seleccionar una prioridad.',
            'priority_id.exists' => 'La prioridad seleccionada no es válida.',
        ];
    }
}
