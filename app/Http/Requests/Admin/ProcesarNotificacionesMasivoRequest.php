<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProcesarNotificacionesMasivoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación aplicables para acciones masivas sobre notificaciones.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
            'accion' => ['required', 'string', 'in:marcar_leido,marcar_no_leido,eliminar'],
        ];
    }

    /**
     * Mensajes personalizados para los errores de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ids.required' => 'Debes seleccionar al menos una notificación.',
            'ids.min' => 'Debes seleccionar al menos una notificación.',
            'accion.required' => 'Debes especificar la acción a realizar.',
            'accion.in' => 'La acción seleccionada no es válida.',
        ];
    }
}
