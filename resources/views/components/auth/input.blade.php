@props(['id', 'type' => 'text', 'name', 'label', 'placeholder' => '', 'required' => false])

<div class="form-group" style="margin-bottom: 1rem;">
    <label for="{{ $id }}" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">{{ $label }}</label>
    <div style="position: relative;">
        <input 
            type="{{ $type }}" 
            id="{{ $id }}" 
            name="{{ $name }}" 
            placeholder="{{ $placeholder }}"
            value="{{ old($name) }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['style' => 'width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box;']) }}
        >
        @if($type === 'password')
            <button type="button" class="toggle-password" tabindex="-1" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #64748b; padding: 0; display: flex; align-items: center; justify-content: center;" onclick="togglePasswordVisibility('{{ $id }}', this)">
                <!-- SVG Ojo Cerrado -->
                <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
            <script>
                function togglePasswordVisibility(inputId, btn) {
                    var input = document.getElementById(inputId);
                    if (input.type === 'password') {
                        input.type = 'text';
                        btn.innerHTML = '<svg class="eye-on" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
                    } else {
                        input.type = 'password';
                        btn.innerHTML = '<svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
                    }
                }
            </script>
        @endif
    </div>
    @error($name)
        <span style="color: #e11d48; font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
    @enderror
</div>
