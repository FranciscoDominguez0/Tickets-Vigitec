<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class BloquearEmailTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'motivo' => ['nullable', 'string', 'max:500'],
            'bloquear_dominio' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'motivo.max' => 'El motivo no puede exceder los 500 caracteres.',
        ];
    }
}
