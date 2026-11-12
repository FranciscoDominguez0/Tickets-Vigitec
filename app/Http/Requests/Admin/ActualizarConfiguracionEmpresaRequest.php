<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarConfiguracionEmpresaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return auth('staff')->check() && auth('staff')->user()->esAdmin();
    }

    /**
     * Reglas de validación para actualizar la configuración de la empresa.
     */
    public function rules(): array
    {
        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_website' => ['nullable', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'company_logo_mode' => ['required', 'in:default,custom'],
            'company_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,gif', 'max:5120'],
            'login_bg_mode' => ['required', 'in:default,custom'],
            'login_background' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,gif', 'max:8192'],
            'active_tab' => ['nullable', 'in:basic,logos,login'],
        ];
    }

    /**
     * Mensajes personalizados de error de validación.
     */
    public function messages(): array
    {
        return [
            'company_name.max' => 'El nombre de la empresa no debe exceder 255 caracteres.',
            'company_logo.image' => 'El archivo del logotipo debe ser una imagen válida.',
            'company_logo.mimes' => 'Formatos permitidos para el logotipo: PNG, JPG, JPEG, WEBP o GIF.',
            'company_logo.max' => 'El logotipo no debe superar los 5 MB.',
            'login_background.image' => 'El archivo de fondo debe ser una imagen válida.',
            'login_background.mimes' => 'Formatos permitidos para el fondo: PNG, JPG, JPEG, WEBP o GIF.',
            'login_background.max' => 'El fondo no debe superar los 8 MB.',
        ];
    }
}
