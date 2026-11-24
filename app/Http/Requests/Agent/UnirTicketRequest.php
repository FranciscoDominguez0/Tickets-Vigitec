<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class UnirTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'ticket_secundario_id' => ['required'],
            'motivo' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_secundario_id.required' => 'Debe indicar el ticket a fusionar.',
            'motivo.max' => 'El motivo no puede exceder los 500 caracteres.',
        ];
    }
}
