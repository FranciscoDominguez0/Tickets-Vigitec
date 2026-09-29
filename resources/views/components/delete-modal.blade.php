@props(['id', 'title' => '¿Estás seguro?', 'submitText' => 'Eliminar', 'formAction' => '', 'method' => 'DELETE', 'message' => 'Esta acción no se puede deshacer.'])

<div class="modal fade custom-dark-modal delete-modal" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <!-- Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-white d-flex align-items-center gap-2" id="{{ $id }}Label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                    {{ $title }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ $formAction }}" method="POST" id="{{ $id }}-form">
                @csrf
                @method($method)
                
                <!-- Body -->
                <div class="modal-body pt-3 pb-4">
                    <p class="mb-0 text-gray-300">{{ $message }}</p>
                    {{ $slot }}
                </div>
                
                <!-- Footer -->
                <div class="modal-footer border-0 pt-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-dark border border-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger fw-bold" style="background: #ef4444; border: none;">
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
/* Estilos adicionales para modal de eliminación */
.delete-modal .modal-content {
    border: 1px solid #7f1d1d; /* Darker red border */
    box-shadow: 0 25px 50px -12px rgba(239, 68, 68, 0.25);
}
.delete-modal .modal-header {
    border-bottom: 1px solid #334155 !important;
}
.delete-modal .modal-footer {
    border-top: 1px solid #334155 !important;
}
.text-gray-300 {
    color: #cbd5e1;
}
</style>
@endpush
@endonce
