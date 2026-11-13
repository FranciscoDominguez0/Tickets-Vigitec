<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarTemaAyudaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para registrar un tema de ayuda.
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
                Rule::unique('help_topics', 'name')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId);
                }),
            ],
            'dept_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId);
                }),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
            'is_public' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del tema de ayuda es obligatorio.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'name.unique' => 'Ya existe un tema de ayuda con este nombre en la empresa.',
            'dept_id.required' => 'Debes asignar un departamento al tema de ayuda.',
            'dept_id.exists' => 'El departamento seleccionado no es válido.',
            'description.max' => 'La descripción no puede exceder los 1000 caracteres.',
        ];
    }
}
