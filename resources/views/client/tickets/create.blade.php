<x-client.layout title="Abrir Nuevo Ticket">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <!-- Navegación / Migas de pan -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left me-1"></i> Mis Tickets
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Abrir Nuevo Ticket</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background-color: #fef2f2; color: #ef4444;">
                            <i class="bi bi-ticket-perforated-fill fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h5 fw-bold mb-1 text-dark">Nueva Solicitud de Soporte</h2>
                            <p class="text-muted small mb-0">Completa el formulario para que nuestro equipo técnico pueda atenderte lo más pronto posible.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('client.tickets.store') }}" method="POST" enctype="multipart/form-data" id="formCrearTicket">
                        @csrf

                        <div class="row g-3 mb-3">
                            @if($temas->isNotEmpty())
                                <div class="col-md-6">
                                    <label for="topic_id" class="form-label fw-semibold text-secondary small text-uppercase">
                                        Tema de Ayuda <span class="text-muted fw-normal">(Opcional)</span>
                                    </label>
                                    <select class="form-select @error('topic_id') is-invalid @enderror" id="topic_id" name="topic_id">
                                        <option value="">-- Selecciona un tema --</option>
                                        @foreach($temas as $tema)
                                            <option value="{{ $tema->id }}" data-dept-id="{{ $tema->dept_id }}" {{ old('topic_id') == $tema->id ? 'selected' : '' }}>
                                                {{ $tema->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('topic_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="{{ $temas->isNotEmpty() ? 'col-md-6' : 'col-12' }}">
                                <label for="dept_id" class="form-label fw-semibold text-secondary small text-uppercase">
                                    Departamento <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('dept_id') is-invalid @enderror" id="dept_id" name="dept_id" required>
                                    <option value="">-- Selecciona el departamento --</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{ $departamento->id }}" {{ old('dept_id') == $departamento->id ? 'selected' : '' }}>
                                            {{ $departamento->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dept_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label fw-semibold text-secondary small text-uppercase">
                                Asunto del Ticket <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   maxlength="80" 
                                   placeholder="Resumen claro del problema o requerimiento (máx. 80 caracteres)" 
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-end small" id="contadorAsunto">0 / 80 caracteres</div>
                        </div>

                        <div class="mb-3">
                            <label for="anydesk" class="form-label fw-semibold text-secondary small text-uppercase">
                                ID de Anydesk / Acceso Remoto <span class="text-muted fw-normal">(Opcional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="bi bi-display"></i></span>
                                <input type="text" 
                                       class="form-control @error('anydesk') is-invalid @enderror" 
                                       id="anydesk" 
                                       name="anydesk" 
                                       value="{{ old('anydesk') }}" 
                                       placeholder="Ej: 123 456 789 (si requiere revisión remota en tu equipo)">
                            </div>
                            @error('anydesk')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label fw-semibold text-secondary small text-uppercase">
                                Descripción Detallada <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('body') is-invalid @enderror" 
                                      id="body" 
                                      name="body" 
                                      rows="6" 
                                      placeholder="Explica detalladamente la situación, pasos para reproducir el fallo o qué necesitas..." 
                                      required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="attachments" class="form-label fw-semibold text-secondary small text-uppercase">
                                Archivos Adjuntos <span class="text-muted fw-normal">(Capturas, documentos o reportes - Máx. 5 archivos de 10 MB c/u)</span>
                            </label>
                            <input class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror" 
                                   type="file" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple 
                                   accept="image/*,.pdf,.doc,.docx,.txt,.mp4">
                            @error('attachments')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('attachments.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <!-- Contenedor de alerta de tamaño en tiempo real -->
                            <div id="alertaAdjuntos" class="alert alert-danger border-0 rounded-3 mt-2 d-none py-2 px-3 small">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                <span id="mensajeAlertaAdjuntos"></span>
                            </div>

                            <!-- Resumen de archivos seleccionados -->
                            <div id="resumenAdjuntos" class="mt-2 d-none">
                                <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border small">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-paperclip text-muted fs-5"></i>
                                        <span id="textoResumenAdjuntos" class="text-muted fw-semibold"></span>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" id="btnLimpiarAdjuntos">
                                        <i class="bi bi-x-circle me-1"></i>Quitar archivos
                                    </button>
                                </div>
                                <ul id="listaArchivosAdjuntos" class="list-group list-group-flush small mt-1 rounded-3 border"></ul>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <a href="{{ route('dashboard') }}" class="btn btn-light px-4 py-2 rounded-pill fw-semibold text-secondary border">
                                Cancelar
                            </a>
                            <button type="submit" id="btnEnviarTicket" class="btn btn-danger px-5 py-2 rounded-pill fw-bold shadow-sm" style="background-color: #ef4444; border-color: #ef4444;">
                                <i class="bi bi-send me-1"></i> <span id="btnTextoEnviar">Enviar Ticket</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Contador de caracteres para el asunto
            const inputAsunto = document.getElementById('subject');
            const contadorAsunto = document.getElementById('contadorAsunto');
            if (inputAsunto && contadorAsunto) {
                const actualizarContador = () => {
                    const actual = inputAsunto.value.length;
                    contadorAsunto.textContent = `${actual} / 80 caracteres`;
                    contadorAsunto.classList.toggle('text-danger', actual >= 80);
                };
                inputAsunto.addEventListener('input', actualizarContador);
                actualizarContador();
            }

            // Sincronización de tema y departamento
            const selectTema = document.getElementById('topic_id');
            const selectDept = document.getElementById('dept_id');
            if (selectTema && selectDept) {
                selectTema.addEventListener('change', function () {
                    const selectedOption = selectTema.options[selectTema.selectedIndex];
                    const deptId = selectedOption.getAttribute('data-dept-id');
                    if (deptId && deptId > 0) {
                        selectDept.value = deptId;
                    }
                });
            }

            // Validación estricta y visual de archivos adjuntos
            const inputAdjuntos = document.getElementById('attachments');
            const alertaAdjuntos = document.getElementById('alertaAdjuntos');
            const mensajeAlerta = document.getElementById('mensajeAlertaAdjuntos');
            const resumenAdjuntos = document.getElementById('resumenAdjuntos');
            const textoResumen = document.getElementById('textoResumenAdjuntos');
            const listaArchivos = document.getElementById('listaArchivosAdjuntos');
            const btnLimpiar = document.getElementById('btnLimpiarAdjuntos');
            const btnEnviar = document.getElementById('btnEnviarTicket');
            const btnTexto = document.getElementById('btnTextoEnviar');
            const formulario = document.getElementById('formCrearTicket');

            const LIMITE_MAX_POR_ARCHIVO = 10 * 1024 * 1024; // 10 MB
            const LIMITE_MAX_TOTAL = 30 * 1024 * 1024; // 30 MB
            const MAX_ARCHIVOS = 5;

            function formatearBytes(bytes) {
                if (bytes < 1024) return bytes + ' B';
                if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
                return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
            }

            function validarArchivos() {
                alertaAdjuntos.classList.add('d-none');
                mensajeAlerta.textContent = '';
                btnEnviar.disabled = false;

                const archivos = inputAdjuntos.files;
                if (!archivos || archivos.length === 0) {
                    resumenAdjuntos.classList.add('d-none');
                    listaArchivos.innerHTML = '';
                    return true;
                }

                if (archivos.length > MAX_ARCHIVOS) {
                    mensajeAlerta.textContent = `Has seleccionado ${archivos.length} archivos. El máximo permitido es de ${MAX_ARCHIVOS} archivos.`;
                    alertaAdjuntos.classList.remove('d-none');
                    btnEnviar.disabled = true;
                    return false;
                }

                let pesoTotal = 0;
                let hayArchivoExcedido = false;
                let nombresExcedidos = [];
                listaArchivos.innerHTML = '';

                for (let i = 0; i < archivos.length; i++) {
                    const archivo = archivos[i];
                    pesoTotal += archivo.size;

                    const item = document.createElement('li');
                    item.className = 'list-group-item d-flex justify-content-between align-items-center py-2 px-3';

                    const esExcedido = archivo.size > LIMITE_MAX_POR_ARCHIVO;
                    if (esExcedido) {
                        hayArchivoExcedido = true;
                        nombresExcedidos.push(`${archivo.name} (${formatearBytes(archivo.size)})`);
                    }

                    item.innerHTML = `
                        <div class="d-flex align-items-center gap-2 text-truncate me-2">
                            <i class="bi ${esExcedido ? 'bi-exclamation-octagon text-danger' : 'bi-file-earmark-check text-success'} fs-5"></i>
                            <span class="text-truncate ${esExcedido ? 'text-danger fw-bold' : 'text-dark'}">${archivo.name}</span>
                        </div>
                        <span class="badge ${esExcedido ? 'bg-danger' : 'bg-secondary'} rounded-pill flex-shrink-0">${formatearBytes(archivo.size)}</span>
                    `;
                    listaArchivos.appendChild(item);
                }

                textoResumen.textContent = `${archivos.length} archivo(s) seleccionado(s) — Total: ${formatearBytes(pesoTotal)} / máx. 30 MB`;
                resumenAdjuntos.classList.remove('d-none');

                if (hayArchivoExcedido) {
                    mensajeAlerta.textContent = `Uno o más archivos superan el límite de 10 MB: ${nombresExcedidos.join(', ')}. Por favor retíralos o reduce su tamaño.`;
                    alertaAdjuntos.classList.remove('d-none');
                    btnEnviar.disabled = true;
                    return false;
                }

                if (pesoTotal > LIMITE_MAX_TOTAL) {
                    mensajeAlerta.textContent = `El tamaño total (${formatearBytes(pesoTotal)}) excede el límite máximo permitido de 30 MB.`;
                    alertaAdjuntos.classList.remove('d-none');
                    btnEnviar.disabled = true;
                    return false;
                }

                return true;
            }

            if (inputAdjuntos) {
                inputAdjuntos.addEventListener('change', validarArchivos);
            }

            if (btnLimpiar) {
                btnLimpiar.addEventListener('click', function () {
                    inputAdjuntos.value = '';
                    validarArchivos();
                });
            }

            if (formulario) {
                formulario.addEventListener('submit', function (e) {
                    if (!validarArchivos()) {
                        e.preventDefault();
                        alertaAdjuntos.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false;
                    }

                    // Estado de envío para evitar doble clic y mostrar retroalimentación
                    btnEnviar.disabled = true;
                    btnTexto.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Enviando ticket...';
                });
            }
        });
    </script>
    @endpush
</x-client.layout>

