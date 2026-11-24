<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class EditarHorasSoporteTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'support_start' => ['nullable', 'date'],
            'support_end' => ['nullable', 'date', 'after_or_equal:support_start'],
        ];
    }

    public function messages(): array
    {
        return [
            'support_start.date' => 'La fecha de inicio de soporte no es válida.',
            'support_end.date' => 'La fecha de fin de soporte no es válida.',
            'support_end.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
        ];
    }
}
