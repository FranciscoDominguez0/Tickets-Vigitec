<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcesarTemasAyudaMasivoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para procesamiento masivo de temas de ayuda.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:help_topics,id'],
            'accion' => ['required', 'string', 'in:habilitar,deshabilitar,hacer_publico,hacer_privado,eliminar,enable,disable,delete'],
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
            'ids.required' => 'Debes seleccionar al menos un tema de ayuda.',
            'ids.min' => 'Debes seleccionar al menos un tema de ayuda.',
            'accion.required' => 'Debes especificar la acción a ejecutar.',
            'accion.in' => 'La acción seleccionada no es válida.',
        ];
    }
}
