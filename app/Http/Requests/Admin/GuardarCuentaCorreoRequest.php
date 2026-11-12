<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarCuentaCorreoRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado tiene permisos de administrador.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para registrar una cuenta de correo.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('email_accounts', 'email')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId);
                }),
            ],
            'name' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'string', 'in:Baja,Normal,Alta,Urgente'],
            'dept_id' => [
                'nullable',
                'integer',
                Rule::exists('departments', 'id')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId);
                }),
            ],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'between:1,65535'],
            'smtp_secure' => ['nullable', 'string', 'in:none,ssl,tls'],
            'smtp_user' => ['nullable', 'string', 'max:255'],
            'smtp_pass' => ['nullable', 'string', 'max:255'],
            'is_default' => ['nullable'],
        ];
    }

    /**
     * Mensajes de error en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debes ingresar un correo electrónico válido.',
            'email.unique' => 'Ya existe una cuenta con este correo electrónico registrada en la empresa.',
            'name.required' => 'El nombre del remitente es obligatorio.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad seleccionada no es válida.',
            'dept_id.exists' => 'El departamento seleccionado no es válido.',
            'smtp_port.between' => 'El puerto SMTP debe estar entre 1 y 65535.',
        ];
    }
}
