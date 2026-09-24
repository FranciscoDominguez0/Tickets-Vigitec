@props(['type' => 'submit'])

<button type="{{ $type }}" class="btn-login" {{ $attributes->merge(['style' => 'width: 100%; padding: 0.75rem; background-color: #0f172a; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 1rem;']) }}>
    {{ $slot }}
</button>
