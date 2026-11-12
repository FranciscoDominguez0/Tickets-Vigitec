<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarSecuenciaRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado tiene permisos de administrador.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para registrar una nueva secuencia numérica.
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
                Rule::unique('sequences', 'name')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId);
                }),
            ],
            'next' => ['required', 'integer', 'min:0'],
            'increment' => ['required', 'integer', 'min:1'],
            'padding' => ['required', 'integer', 'between:0,20'],
        ];
    }

    /**
     * Mensajes de validación en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la secuencia es obligatorio.',
            'name.unique' => 'Ya existe una secuencia con este nombre en la empresa.',
            'next.required' => 'El siguiente número es obligatorio.',
            'next.min' => 'El siguiente número debe ser mayor o igual a 0.',
            'increment.required' => 'El incremento es obligatorio.',
            'increment.min' => 'El incremento debe ser de al menos 1.',
            'padding.required' => 'El relleno de dígitos es obligatorio.',
            'padding.between' => 'El relleno de dígitos debe estar entre 0 y 20.',
        ];
    }
}
