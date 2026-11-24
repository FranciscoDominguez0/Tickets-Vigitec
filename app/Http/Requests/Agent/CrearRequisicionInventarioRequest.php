<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class CrearRequisicionInventarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('staff')->check();
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Debe agregar al menos un artículo a la solicitud.',
            'items.min' => 'Debe agregar al menos un artículo a la solicitud.',
            'items.*.product_name.required' => 'El nombre del producto o material es obligatorio.',
            'items.*.quantity.required' => 'La cantidad requerida es obligatoria.',
            'items.*.quantity.min' => 'La cantidad mínima por artículo debe ser 1.',
        ];
    }
}
