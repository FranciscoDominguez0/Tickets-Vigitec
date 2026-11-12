<x-agent.admin-layout>
    @push('styles')
    <style>
        .settings-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .settings-hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .settings-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .settings-card .card-header {
            background-color: #121417;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.1rem 1.5rem;
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .settings-card .card-body {
            padding: 1.5rem;
        }

        .settings-card .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        .settings-card .form-control,
        .settings-card .form-select {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.92rem;
            transition: all 0.2s;
        }

        .settings-card .form-control:focus,
        .settings-card .form-select:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }

        .preview-pill {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            padding: 0.25rem 0.75rem;
            font-family: monospace;
            font-weight: bold;
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="settings-hero">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="settings-hero-icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <div>
                    <h1 class="h4 fw-bold text-white mb-1">Configuración de Solicitudes y Números de Tickets</h1>
                    <p class="text-secondary small mb-0">Gestiona la numeración automática de tickets, formatos, estados por defecto y límites de apertura.</p>
                </div>
            </div>
            <a href="{{ route('admin.sequences') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                <i class="bi bi-arrow-repeat me-1"></i> Gestionar Secuencias
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-4 d-flex align-items-center gap-2" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-4 d-flex align-items-center gap-2" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <form action="{{ route('admin.settings.tickets.store') }}" method="POST">
        @csrf

        <!-- 1. Numeración de Tickets -->
        <div class="settings-card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-123 text-danger"></i>
                <span>Numeración y Secuencias de Tickets</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="ticket_number_format" class="form-label">Formato de Número de Ticket por Defecto *</label>
                        <input type="text" 
                               name="ticket_number_format" 
                               id="ticket_number_format" 
                               class="form-control @error('ticket_number_format') is-invalid @enderror" 
                               value="{{ old('ticket_number_format', $formatoNumero) }}" 
                               required>
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <span class="text-secondary small">Ejemplo generado:</span>
                            <span class="preview-pill" id="vistaPreviaFormato">{{ $ejemploNumero }}</span>
                        </div>
                        <div class="form-text text-secondary small">
                            Usa almohadillas (<code>#</code>) para representar dígitos numéricos aleatorios (ej: <code>######</code> genera números de 6 cifras).
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="ticket_sequence_id" class="form-label">Secuencia Numérica por Defecto *</label>
                        <div class="input-group">
                            <select name="ticket_sequence_id" id="ticket_sequence_id" class="form-select @error('ticket_sequence_id') is-invalid @enderror">
                                <option value="0" {{ old('ticket_sequence_id', $secuenciaId) === '0' ? 'selected' : '' }}>— Aleatorio (Usa Formato) —</option>
                                @foreach($secuencias as $secuencia)
                                    <option value="{{ $secuencia->id }}" {{ old('ticket_sequence_id', $secuenciaId) == (string)$secuencia->id ? 'selected' : '' }}>
                                        {{ $secuencia->name }} (Siguiente: {{ $secuencia->obtenerNumeroFormateado() }})
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.sequences') }}" class="btn btn-outline-secondary" title="Administrar secuencias">
                                <i class="bi bi-gear"></i> Gestionar
                            </a>
                        </div>
                        <div class="form-text text-secondary small">
                            Si seleccionas una secuencia, los tickets se numerarán correlativamente según el incremento y padding configurados.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Valores Predeterminados -->
        <div class="settings-card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-sliders text-danger"></i>
                <span>Valores Predeterminados del Sistema</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="default_ticket_status_id" class="form-label">Estado Inicial Predeterminado</label>
                        <select name="default_ticket_status_id" id="default_ticket_status_id" class="form-select">
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}" {{ old('default_ticket_status_id', $estadoPorDefecto) == $estado->id ? 'selected' : '' }}>
                                    {{ $estado->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="default_priority_id" class="form-label">Prioridad Predeterminada</label>
                        <select name="default_priority_id" id="default_priority_id" class="form-select">
                            @foreach($prioridades as $prioridad)
                                <option value="{{ $prioridad->id }}" {{ old('default_priority_id', $prioridadPorDefecto) == $prioridad->id ? 'selected' : '' }}>
                                    {{ $prioridad->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="default_help_topic" class="form-label">Tema de Ayuda por Defecto</label>
                        <select name="default_help_topic" id="default_help_topic" class="form-select">
                            <option value="0">— Ninguno —</option>
                            @foreach($temas as $tema)
                                <option value="{{ $tema->id }}" {{ old('default_help_topic', $temaPorDefecto) == $tema->id ? 'selected' : '' }}>
                                    {{ $tema->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Límites y Reglas de Apertura -->
        <div class="settings-card">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock text-danger"></i>
                <span>Límites y Restricciones de Creación</span>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label for="max_open_tickets" class="form-label">Máximo de Tickets Abiertos por Usuario</label>
                        <div class="input-group">
                            <input type="number" 
                                   name="max_open_tickets" 
                                   id="max_open_tickets" 
                                   class="form-control" 
                                   value="{{ old('max_open_tickets', $maxTicketsAbiertos) }}" 
                                   min="0" 
                                   max="999">
                            <span class="input-group-text bg-dark text-secondary border-secondary">tickets</span>
                        </div>
                        <div class="form-text text-secondary small">Coloca 0 para permitir tickets abiertos ilimitados por usuario final.</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label d-block">Bloqueo por Firma Pendiente</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="block_new_if_signature_pending" 
                                   id="block_new_if_signature_pending" 
                                   value="1" 
                                   {{ old('block_new_if_signature_pending', $bloquearPorFirma) ? 'checked' : '' }}>
                            <label class="form-check-label text-white small" for="block_new_if_signature_pending">
                                Bloquear creación de nuevos tickets si el usuario tiene firmas pendientes
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="ticket_max_file_mb" class="form-label">Tamaño Máximo por Archivo Adjunto *</label>
                        <div class="input-group">
                            <input type="number" 
                                   name="ticket_max_file_mb" 
                                   id="ticket_max_file_mb" 
                                   class="form-control" 
                                   value="{{ old('ticket_max_file_mb', $tamanoMaxArchivoMb) }}" 
                                   min="1" 
                                   max="256" 
                                   required>
                            <span class="input-group-text bg-dark text-secondary border-secondary">MB</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="ticket_max_uploads" class="form-label">Máximo de Archivos por Mensaje *</label>
                        <input type="number" 
                               name="ticket_max_uploads" 
                               id="ticket_max_uploads" 
                               class="form-control" 
                               value="{{ old('ticket_max_uploads', $maxSubidas) }}" 
                               min="0" 
                               max="20" 
                               required>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-danger rounded-pill px-5 py-2 fw-bold shadow-sm" style="background-color: #ef4444; border-color: #ef4444;">
                <i class="bi bi-save me-1"></i> Guardar Cambios
            </button>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputFormato = document.getElementById('ticket_number_format');
            const selectSecuencia = document.getElementById('ticket_sequence_id');
            const previewPill = document.getElementById('vistaPreviaFormato');

            const actualizarVistaPrevia = () => {
                if (selectSecuencia && selectSecuencia.value !== '0') {
                    const opt = selectSecuencia.options[selectSecuencia.selectedIndex];
                    previewPill.textContent = opt.textContent.split('(Siguiente: ')[1]?.replace(')', '') || 'Secuencia activa';
                } else if (inputFormato) {
                    let formato = inputFormato.value;
                    let resultado = '';
                    for (let i = 0; i < formato.length; i++) {
                        resultado += formato[i] === '#' ? Math.floor(Math.random() * 10) : formato[i];
                    }
                    previewPill.textContent = resultado || '######';
                }
            };

            if (inputFormato) inputFormato.addEventListener('input', actualizarVistaPrevia);
            if (selectSecuencia) selectSecuencia.addEventListener('change', actualizarVistaPrevia);
        });
    </script>
    @endpush
</x-agent.admin-layout>

