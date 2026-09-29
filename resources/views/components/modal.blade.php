@props(['id', 'title', 'submitText' => 'Guardar', 'formAction' => '', 'method' => 'POST'])

<div class="modal fade custom-dark-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="{{ $id }}Label">
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ $formAction }}" method="POST" id="{{ $id }}-form">
                @csrf
                @if(strtoupper($method) !== 'POST')
                    @method($method)
                @endif
                
                <!-- Body -->
                <div class="modal-body pt-3 pb-4">
                    {{ $slot }}
                </div>
                
                <!-- Footer -->
                <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold" style="background: #3b82f6; border: none;">
                        {{ $submitText }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@once
@push('styles')
<style>
/* Estilos premium oscuros para los modales */
.custom-dark-modal .modal-content {
    background-color: #1e293b; /* Dark slate */
    border: 1px solid #334155;
    border-radius: 12px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

.custom-dark-modal .modal-header {
    border-bottom: 1px solid #334155 !important;
    padding: 1rem 1.25rem;
}

.custom-dark-modal .modal-body {
    color: #cbd5e1;
}

.custom-dark-modal .modal-footer {
    border-top: 1px solid #334155 !important;
    padding: 1rem 1.25rem;
}

.custom-dark-modal .form-label {
    color: #94a3b8;
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 0.4rem;
}

.custom-dark-modal .form-control,
.custom-dark-modal .form-select {
    background-color: #0f172a;
    border: 1px solid #334155;
    color: #f8fafc;
    border-radius: 8px;
    padding: 0.6rem 0.8rem;
}

.custom-dark-modal .form-control:focus,
.custom-dark-modal .form-select:focus {
    background-color: #0f172a;
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    color: #f8fafc;
}

.custom-dark-modal .btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.5;
}
.custom-dark-modal .btn-close-white:hover {
    opacity: 1;
}
</style>
@endpush
@endonce
