<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GuardarBanlistRequest extends FormRequest
{
    /**
     * Determina si el usuario autenticado tiene permisos de administrador.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para registrar una entrada en la lista de prohibidos.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:email,domain'],
            'notes' => ['nullable', 'string', 'max:1000'],
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
            'value.required' => 'Debes ingresar una dirección de correo o un dominio a prohibir.',
            'type.required' => 'El tipo de regla es obligatorio.',
            'type.in' => 'El tipo de regla seleccionado no es válido.',
            'notes.max' => 'Las notas no pueden exceder los 1000 caracteres.',
        ];
    }
}
