<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrearDepartamentoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para crear un departamento.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $empresaId = (int) (auth('staff')->user()->empresa_id ?? 1);

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('departments', 'name')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId);
                }),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable'],
            'requires_report' => ['nullable'],
        ];
    }

    /**
     * Mensajes de validación personalizados en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del departamento es requerido.',
            'name.string' => 'El nombre del departamento debe ser una cadena de texto.',
            'name.max' => 'El nombre del departamento no debe superar los 100 caracteres.',
            'name.unique' => 'Ya existe un departamento con ese nombre. Por favor usa otro nombre.',
            'description.max' => 'La descripción no debe superar los 1000 caracteres.',
        ];
    }
}
