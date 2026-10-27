@php
    $mensajes = [];
    if (session()->has('exito')) {
        $mensajes[] = ['tipo' => 'success', 'texto' => session('exito')];
    } elseif (session()->has('success')) {
        $mensajes[] = ['tipo' => 'success', 'texto' => session('success')];
    } elseif (session()->has('status')) {
        $mensajes[] = ['tipo' => 'success', 'texto' => session('status')];
    }

    if (session()->has('error')) {
        $mensajes[] = ['tipo' => 'error', 'texto' => session('error')];
    }

    if (session()->has('advertencia')) {
        $mensajes[] = ['tipo' => 'warning', 'texto' => session('advertencia')];
    } elseif (session()->has('warning')) {
        $mensajes[] = ['tipo' => 'warning', 'texto' => session('warning')];
    }

    if (session()->has('info')) {
        $mensajes[] = ['tipo' => 'info', 'texto' => session('info')];
    }

    $colores = [
        'success' => ['bg' => 'rgba(34, 197, 94, 0.95)', 'icon' => 'bi-check-circle-fill', 'border' => 'rgba(34, 197, 94, 0.4)'],
        'error'   => ['bg' => 'rgba(239, 68, 68, 0.95)', 'icon' => 'bi-x-circle-fill', 'border' => 'rgba(239, 68, 68, 0.4)'],
        'warning' => ['bg' => 'rgba(245, 158, 11, 0.95)', 'icon' => 'bi-exclamation-triangle-fill', 'border' => 'rgba(245, 158, 11, 0.4)'],
        'info'    => ['bg' => 'rgba(59, 130, 246, 0.95)', 'icon' => 'bi-info-circle-fill', 'border' => 'rgba(59, 130, 246, 0.4)'],
    ];
@endphp

<div class="toast-container position-fixed top-0 end-0 p-3" id="contenedorToastsGlobal" style="z-index: 9999; top: 20px !important; right: 20px !important;">
    @foreach ($mensajes as $item)
        @php
            $config = $colores[$item['tipo']] ?? $colores['success'];
        @endphp
        <div class="toast align-items-center text-white border-0 custom-toast custom-toast-{{ $item['tipo'] }} show shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="background: {{ $config['bg'] }}; border: 1px solid {{ $config['border'] }} !important; box-shadow: 0 10px 25px rgba(0,0,0,0.5); backdrop-filter: blur(8px); border-radius: 12px; margin-bottom: 12px; min-width: 300px;">
            <div class="d-flex p-1">
                <div class="toast-body d-flex align-items-center gap-3 fw-semibold py-2">
                    <i class="bi {{ $config['icon'] }} fs-4" style="color: rgba(255,255,255,0.95);"></i>
                    <span style="font-size: 0.92rem; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">{{ $item['texto'] }}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar" style="opacity: 0.8;"></button>
            </div>
        </div>
    @endforeach
</div>

@once
@push('styles')
<style>
.custom-toast {
    animation: toastSlideInRight 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.custom-toast:not(.show) {
    opacity: 0 !important;
    transform: translateX(100%);
}
@keyframes toastSlideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar y auto-ocultar los toasts iniciales del servidor
    function autoOcultarToast(toastEl) {
        setTimeout(() => {
            toastEl.classList.remove('show');
            setTimeout(() => toastEl.remove(), 350);
        }, 4500);

        const btnCerrar = toastEl.querySelector('.btn-close');
        if (btnCerrar) {
            btnCerrar.addEventListener('click', function() {
                toastEl.classList.remove('show');
                setTimeout(() => toastEl.remove(), 350);
            });
        }
    }

    document.querySelectorAll('.custom-toast').forEach(autoOcultarToast);

    // Función global para mostrar Toasts dinámicamente desde JavaScript
    window.mostrarToast = function (mensaje, tipo = 'success') {
        const contenedor = document.getElementById('contenedorToastsGlobal');
        if (!contenedor) return;

        const normalizarTipo = (tipo === 'exito' ? 'success' : (tipo === 'danger' ? 'error' : (tipo === 'advertencia' ? 'warning' : tipo)));

        const iconos = {
            success: 'bi-check-circle-fill',
            error: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill',
            info: 'bi-info-circle-fill'
        };

        const colores = {
            success: { bg: 'rgba(34, 197, 94, 0.95)', border: 'rgba(34, 197, 94, 0.4)' },
            error: { bg: 'rgba(239, 68, 68, 0.95)', border: 'rgba(239, 68, 68, 0.4)' },
            warning: { bg: 'rgba(245, 158, 11, 0.95)', border: 'rgba(245, 158, 11, 0.4)' },
            info: { bg: 'rgba(59, 130, 246, 0.95)', border: 'rgba(59, 130, 246, 0.4)' }
        };

        const config = colores[normalizarTipo] || colores.success;
        const icono = iconos[normalizarTipo] || iconos.success;

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white border-0 custom-toast custom-toast-${normalizarTipo} show shadow-lg`;
        toast.setAttribute('role', 'alert');
        toast.style.cssText = `background: ${config.bg}; border: 1px solid ${config.border} !important; box-shadow: 0 10px 25px rgba(0,0,0,0.5); backdrop-filter: blur(8px); border-radius: 12px; margin-bottom: 12px; min-width: 300px;`;

        toast.innerHTML = `
            <div class="d-flex p-1">
                <div class="toast-body d-flex align-items-center gap-3 fw-semibold py-2">
                    <i class="bi ${icono} fs-4" style="color: rgba(255,255,255,0.95);"></i>
                    <span style="font-size: 0.92rem; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">${mensaje}</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar" style="opacity: 0.8;"></button>
            </div>
        `;

        contenedor.appendChild(toast);
        autoOcultarToast(toast);
    };

    // Escuchar evento personalizado de usuario creado para mostrar toast
    window.addEventListener('usuario-creado', function(e) {
        if (window.mostrarToast && e.detail) {
            window.mostrarToast(`Usuario ${e.detail.nombre_completo || ''} registrado con éxito.`, 'success');
        }
    });

    // Escuchar evento personalizado de organización creada para mostrar toast
    window.addEventListener('organizacion-creada', function(e) {
        if (window.mostrarToast && e.detail) {
            window.mostrarToast(`Organización ${e.detail.name || e.detail.nombre || ''} registrada con éxito.`, 'success');
        }
    });

    // Escuchar evento general de formulario modal con éxito
    window.addEventListener('formulario-modal-exito', function(e) {
        if (window.mostrarToast && e.detail && (e.detail.mensaje || e.detail.exito)) {
            if (!e.detail.usuario && !e.detail.organizacion) {
                window.mostrarToast(e.detail.mensaje || e.detail.exito, 'success');
            }
        }
    });
});
</script>
@endpush
@endonce
