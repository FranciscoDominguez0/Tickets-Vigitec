<?php

namespace App\Http\Requests\Client;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResponderTicketClienteRequest extends FormRequest
{
    /**
     * Determina si el cliente autenticado puede realizar la solicitud.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Reglas de validación para enviar respuesta a un ticket.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:2'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,txt,mp4,webm,mov,mkv'],
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
            'body.required' => 'El mensaje de respuesta no puede estar vacío.',
            'body.min' => 'El mensaje debe contener al menos 2 caracteres.',
            'attachments.max' => 'Puedes adjuntar un máximo de 5 archivos.',
            'attachments.*.max' => 'Cada archivo no puede superar los 10 MB.',
            'attachments.*.mimes' => 'El formato del archivo adjunto no es válido.',
        ];
    }
}
