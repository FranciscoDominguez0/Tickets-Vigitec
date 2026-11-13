<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarPersonalRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación aplicables para actualizar un agente.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $agenteId = (int) $this->route('id');

        return [
            'firstname' => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'username' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('staff', 'username')->ignore($agenteId),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('staff', 'email')->ignore($agenteId),
            ],
            'dept_id' => ['nullable', 'integer', 'exists:departments,id'],
            'role' => ['required', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'signature' => ['nullable', 'string', 'max:1000'],
            'departamentos_adicionales' => ['nullable', 'array'],
            'departamentos_adicionales.*' => ['integer', 'exists:departments,id'],
        ];
    }

    /**
     * Mensajes de error personalizados.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'firstname.required' => 'El nombre es obligatorio.',
            'lastname.required' => 'El apellido es obligatorio.',
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'Este nombre de usuario ya está en uso por otro agente.',
            'username.alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            'dept_id.exists' => 'El departamento seleccionado no es válido.',
            'role.required' => 'El rol del agente es obligatorio.',
        ];
    }
}
