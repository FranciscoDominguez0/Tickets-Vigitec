@props(['type' => 'success', 'message'])

<div class="alert alert-{{ $type }}" style="padding: 1rem; border-radius: 4px; margin-bottom: 1rem; background-color: {{ $type === 'danger' ? '#fee2e2' : '#dcfce7' }}; color: {{ $type === 'danger' ? '#991b1b' : '#166534' }}; border: 1px solid {{ $type === 'danger' ? '#f87171' : '#86efac' }};">
    {{ $message }}
</div>
