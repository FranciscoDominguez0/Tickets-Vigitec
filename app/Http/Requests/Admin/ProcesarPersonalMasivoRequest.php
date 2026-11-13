<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcesarPersonalMasivoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación aplicables para acciones masivas sobre el personal.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
            'accion' => ['required', 'string', 'in:activar,desactivar,eliminar'],
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
            'ids.required' => 'Debes seleccionar al menos un agente.',
            'ids.min' => 'Debes seleccionar al menos un agente.',
            'accion.required' => 'La acción a realizar es obligatoria.',
            'accion.in' => 'La acción seleccionada no es válida.',
        ];
    }
}
