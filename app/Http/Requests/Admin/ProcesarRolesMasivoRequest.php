<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcesarRolesMasivoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para el procesamiento masivo de roles.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer'],
            'accion' => ['sometimes', 'string', 'in:habilitar,deshabilitar,eliminar,enable,disable,delete'],
            'a' => ['sometimes', 'string', 'in:habilitar,deshabilitar,eliminar,enable,disable,delete'],
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
            'ids.required' => 'Debe seleccionar al menos un rol.',
            'ids.min' => 'Debe seleccionar al menos un rol.',
            'accion.in' => 'La acción seleccionada no es válida.',
            'a.in' => 'La acción seleccionada no es válida.',
        ];
    }
}
