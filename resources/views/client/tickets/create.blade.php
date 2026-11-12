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
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                            <a href="{{ route('dashboard') }}" class="btn btn-light px-4 py-2 rounded-pill fw-semibold text-secondary border">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger px-5 py-2 rounded-pill fw-bold shadow-sm" style="background-color: #ef4444; border-color: #ef4444;">
                                <i class="bi bi-send me-1"></i> Enviar Ticket
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
        });
    </script>
    @endpush
</x-client.layout>
