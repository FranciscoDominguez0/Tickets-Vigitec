@props(['type' => 'submit'])

<button type="{{ $type }}" class="btn-login" {{ $attributes }}>
    {{ $slot }}
</button>
