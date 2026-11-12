<?php

namespace App\Http\Requests\Client;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrearTicketClienteRequest extends FormRequest
{
    /**
     * Determina si el cliente autenticado puede realizar la solicitud.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Reglas de validación para la creación de tickets desde el portal de clientes.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $empresaId = (int) (auth()->user()->empresa_id ?? 1);

        return [
            'dept_id' => [
                'required',
                'integer',
                Rule::exists('departments', 'id')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId)->where('is_active', 1);
                }),
            ],
            'topic_id' => [
                'nullable',
                'integer',
                Rule::exists('help_topics', 'id')->where(function ($query) use ($empresaId) {
                    return $query->where('empresa_id', $empresaId)->where('is_active', 1);
                }),
            ],
            'subject' => ['required', 'string', 'max:80'],
            'body' => ['required', 'string', 'min:5'],
            'anydesk' => ['nullable', 'string', 'max:50'],
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
            'dept_id.required' => 'Debes seleccionar un departamento para tu solicitud.',
            'dept_id.exists' => 'El departamento seleccionado no es válido o no está disponible.',
            'topic_id.exists' => 'El tema de ayuda seleccionado no es válido.',
            'subject.required' => 'El asunto del ticket es obligatorio.',
            'subject.max' => 'El asunto no puede superar los 80 caracteres.',
            'body.required' => 'La descripción o detalle del problema es obligatoria.',
            'body.min' => 'La descripción debe tener al menos 5 caracteres.',
            'attachments.max' => 'Puedes adjuntar un máximo de 5 archivos.',
            'attachments.*.max' => 'Cada archivo no puede superar los 10 MB.',
            'attachments.*.mimes' => 'El formato del archivo adjunto no es válido. Formatos permitidos: imágenes, PDF, documentos de Office y videos cortos.',
        ];
    }
}
