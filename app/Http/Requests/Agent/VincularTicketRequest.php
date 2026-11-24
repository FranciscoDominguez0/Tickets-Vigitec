<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class VincularTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'ticket_vinculado_id' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'ticket_vinculado_id.required' => 'Debe especificar el ticket a vincular.',
        ];
    }
}
