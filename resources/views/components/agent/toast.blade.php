@if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        @foreach (['success', 'error', 'warning', 'info'] as $msg)
            @if(session()->has($msg))
                @php
                    $colors = [
                        'success' => ['bg' => 'rgba(34, 197, 94, 0.95)', 'icon' => 'bi-check-circle-fill', 'border' => 'rgba(34, 197, 94, 0.4)'],
                        'error'   => ['bg' => 'rgba(239, 68, 68, 0.95)', 'icon' => 'bi-x-circle-fill', 'border' => 'rgba(239, 68, 68, 0.4)'],
                        'warning' => ['bg' => 'rgba(245, 158, 11, 0.95)', 'icon' => 'bi-exclamation-triangle-fill', 'border' => 'rgba(245, 158, 11, 0.4)'],
                        'info'    => ['bg' => 'rgba(59, 130, 246, 0.95)', 'icon' => 'bi-info-circle-fill', 'border' => 'rgba(59, 130, 246, 0.4)'],
                    ];
                    $config = $colors[$msg];
                @endphp
                
                <div class="toast align-items-center text-white border-0 custom-toast custom-toast-{{ $msg }} show" role="alert" aria-live="assertive" aria-atomic="true" style="background: {{ $config['bg'] }}; border: 1px solid {{ $config['border'] }} !important; box-shadow: 0 10px 25px rgba(0,0,0,0.5); backdrop-filter: blur(8px); border-radius: 12px; margin-bottom: 12px;">
                    <div class="d-flex p-1">
                        <div class="toast-body d-flex align-items-center gap-3 fw-semibold py-2">
                            <i class="bi {{ $config['icon'] }} fs-4" style="color: rgba(255,255,255,0.9);"></i>
                            <span style="font-size: 0.95rem; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">{{ session($msg) }}</span>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" style="opacity: 0.8;"></button>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializar y auto-ocultar los toasts
            let toastElList = [].slice.call(document.querySelectorAll('.custom-toast'));
            toastElList.map(function (toastEl) {
                // Auto ocultar después de 4.5 segundos
                setTimeout(() => {
                    toastEl.classList.remove('show');
                    setTimeout(() => toastEl.remove(), 300); // Dar tiempo a la animación de salida
                }, 4500);
                
                // Botón de cerrar
                toastEl.querySelector('.btn-close').addEventListener('click', function() {
                    toastEl.classList.remove('show');
                    setTimeout(() => toastEl.remove(), 300);
                });
            });
        });
    </script>

    <style>
        .custom-toast {
            animation: slideInRight 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .custom-toast:not(.show) {
            opacity: 0 !important;
            transform: translateX(100%);
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
@endif
