@props([
    'id',
    'titulo' => null,
    'title' => null, // compatibilidad
    'subtitulo' => null,
    'icono' => null,
    'colorIcono' => 'text-danger',
    'accion' => '',
    'formAction' => '', // compatibilidad
    'metodo' => 'POST',
    'method' => 'POST', // compatibilidad
    'textoBoton' => 'Guardar',
    'submitText' => null, // compatibilidad
    'iconoBoton' => 'bi-save',
    'colorBoton' => 'btn-danger',
    'tamano' => 'modal-lg',
    'centrado' => true,
    'estatico' => true,
    'modoAjax' => false,
])

@php
    $tituloFinal = $titulo ?? ($title ?? 'Ventana Modal');
    $accionFinal = !empty($accion) ? $accion : $formAction;
    $metodoFinal = strtoupper($metodo !== 'POST' ? $metodo : $method);
    $textoBotonFinal = $submitText ?? $textoBoton;
@endphp

<div class="modal fade custom-dark-modal modal-reutilizable" 
     id="{{ $id }}" 
     tabindex="-1" 
     aria-labelledby="{{ $id }}Label" 
     aria-hidden="true" 
     @if($estatico) data-bs-backdrop="static" @endif>
    
    <div class="modal-dialog {{ $tamano }} {{ $centrado ? 'modal-dialog-centered' : '' }}">
        <div class="modal-content custom-modal-content shadow-lg">
            
            <!-- Encabezado del Modal -->
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <div class="d-flex align-items-center gap-3">
                    @if ($icono)
                        <div class="modal-header-icon-box">
                            <i class="bi {{ $icono }} {{ $colorIcono }}"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0" id="{{ $id }}Label">
                            {{ $tituloFinal }}
                        </h5>
                        @if ($subtitulo)
                            <p class="text-muted small mb-0">{{ $subtitulo }}</p>
                        @endif
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- Formulario o Contenedor -->
            @if ($accionFinal)
                <form action="{{ $accionFinal }}" method="{{ in_array($metodoFinal, ['GET', 'POST']) ? $metodoFinal : 'POST' }}" id="{{ $id }}-formulario" class="formulario-modal" {{ $modoAjax ? 'data-ajax=true' : '' }}>
                    @csrf
                    @if (!in_array($metodoFinal, ['GET', 'POST']))
                        @method($metodoFinal)
                    @endif
            @else
                <div id="{{ $id }}-contenedor">
            @endif

                <!-- Cuerpo del Modal -->
                <div class="modal-body px-4 py-3">
                    <!-- Contenedor dinámico de alertas de error en modal -->
                    <div class="alerta-errores-modal alert alert-danger border-0 rounded-3 shadow-sm d-none" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">
                        <div class="fw-bold mb-1 d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Corrige los siguientes errores:</span>
                        </div>
                        <ul class="mb-0 ps-3 small lista-errores-modal"></ul>
                    </div>

                    {{ $slot }}
                </div>

                <!-- Pie de Modal (Footer) -->
                @isset($footer)
                    <div class="modal-footer border-0 pt-2 pb-3 px-4 d-flex justify-content-end gap-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08) !important;">
                        {{ $footer }}
                    </div>
                @else
                    <div class="modal-footer border-0 pt-2 pb-3 px-4 d-flex justify-content-end gap-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08) !important;">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px;">
                            Cancelar
                        </button>
                        @if ($accionFinal)
                            <button type="submit" class="btn {{ $colorBoton }} px-4 fw-bold boton-modal-submit" style="border-radius: 8px;">
                                <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true"></span>
                                @if ($iconoBoton)
                                    <i class="bi {{ $iconoBoton }} me-1"></i>
                                @endif
                                <span>{{ $textoBotonFinal }}</span>
                            </button>
                        @endif
                    </div>
                @endisset

            @if ($accionFinal)
                </form>
            @else
                </div>
            @endif

        </div>
    </div>
</div>

@once
@push('styles')
<style>
/* Estilos oscuros modernos para modales reutilizables */
.custom-modal-content {
    background-color: #16191d;
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 14px;
    color: #f8fafc;
}

.modal-header-icon-box {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}

.modal-reutilizable .form-label {
    font-size: 0.82rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 0.35rem;
}

.modal-reutilizable .form-control,
.modal-reutilizable .form-select {
    background-color: #111317;
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    padding: 0.55rem 0.85rem;
    border-radius: 8px;
    font-size: 0.9rem;
    transition: all 0.2s ease-in-out;
}

.modal-reutilizable .form-control:focus,
.modal-reutilizable .form-select:focus {
    background-color: #111317;
    border-color: #ef4444;
    color: #fff;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
}

.modal-reutilizable .form-hint {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.45);
    margin-top: 0.25rem;
}

.modal-reutilizable .form-hint code {
    color: #f87171;
    background: rgba(239, 68, 68, 0.1);
    padding: 1px 4px;
    border-radius: 4px;
}

.modal-reutilizable .btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.6;
}

.modal-reutilizable .btn-close-white:hover {
    opacity: 1;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Manejo de envío AJAX para cualquier formulario modal con data-ajax="true"
    document.querySelectorAll('.modal-reutilizable .formulario-modal[data-ajax="true"]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const modalElement = form.closest('.modal');
            const alertBox = form.querySelector('.alerta-errores-modal');
            const errorList = form.querySelector('.lista-errores-modal');
            const submitBtn = form.querySelector('.boton-modal-submit');
            const spinner = submitBtn ? submitBtn.querySelector('.spinner-border') : null;

            if (alertBox) alertBox.classList.add('d-none');
            if (errorList) errorList.innerHTML = '';
            if (spinner) spinner.classList.remove('d-none');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method || 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]')?.value
                },
                body: formData
            })
            .then(async response => {
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw data;
                }
                return data;
            })
            .then(res => {
                // Éxito: cerrar modal
                if (modalElement && window.bootstrap && window.bootstrap.Modal) {
                    const bsModal = window.bootstrap.Modal.getInstance(modalElement) || new window.bootstrap.Modal(modalElement);
                    bsModal.hide();
                }

                // Disparar evento global según entidad (ej. usuario-creado, organizacion-creada, departamento-creado)
                const evento = form.dataset.eventoExito || 'formulario-modal-exito';
                window.dispatchEvent(new CustomEvent(evento, { detail: res }));

                if (res.usuario) {
                    window.dispatchEvent(new CustomEvent('usuario-creado', { detail: res.usuario }));
                    const userSelect = document.querySelector('select[name="user_id"]');
                    if (userSelect) {
                        const opt = new Option(`${res.usuario.nombre_completo} (${res.usuario.email})`, res.usuario.id, true, true);
                        userSelect.add(opt);
                    }
                }

                form.reset();
            })
            .catch(err => {
                if (alertBox && errorList) {
                    alertBox.classList.remove('d-none');
                    if (err.errors) {
                        Object.values(err.errors).forEach(errArray => {
                            errArray.forEach(msg => {
                                const li = document.createElement('li');
                                li.textContent = msg;
                                errorList.appendChild(li);
                            });
                        });
                    } else {
                        const li = document.createElement('li');
                        li.textContent = err.message || 'Ocurrió un error al procesar la solicitud.';
                        errorList.appendChild(li);
                    }
                }
            })
            .finally(() => {
                if (spinner) spinner.classList.add('d-none');
                if (submitBtn) submitBtn.disabled = false;
            });
        });
    });
});
</script>
@endpush
@endonce
