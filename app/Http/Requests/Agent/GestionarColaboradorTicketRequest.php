<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class GestionarColaboradorTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico del colaborador es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.max' => 'El correo no puede tener más de 255 caracteres.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
        ];
    }
}
