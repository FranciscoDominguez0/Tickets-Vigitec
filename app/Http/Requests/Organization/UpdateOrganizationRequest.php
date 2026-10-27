<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organizacionId = $this->route('id') ?? $this->route('organization');

        return [
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('organizations', 'name')->ignore($organizacionId),
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'phone_ext' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'plain_text_emails' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la organización es obligatorio.',
            'name.unique' => 'Ya existe una organización registrada con este nombre.',
            'name.max' => 'El nombre no debe exceder 150 caracteres.',
        ];
    }
}
