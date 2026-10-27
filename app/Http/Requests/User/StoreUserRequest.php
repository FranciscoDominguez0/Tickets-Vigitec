<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:150'],
            'organization_id' => ['nullable', 'integer', 'exists:organizations,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'empresa_id' => ['nullable', 'integer'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'status' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'El nombre es obligatorio.',
            'lastname.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo ya se encuentra registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'organization_id.exists' => 'La organización seleccionada no es válida.',
            'department_id.exists' => 'El departamento seleccionado no es válido.',
        ];
    }
}
