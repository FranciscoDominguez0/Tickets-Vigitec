<x-agent.layout>
    <div class="tickets-shell open-ticket-shell">
        <div class="tickets-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <div>
                    <h1>Abrir nuevo Ticket</h1>
                    <div class="sub">Crea un ticket de soporte para un cliente</div>
                </div>
            </div>
        </div>

        <form action="{{ route('agent.tickets.store') }}" method="POST" id="form-open-ticket">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger mb-4 rounded-3 shadow-sm border-0" style="background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Búsqueda/Selección de Cliente -->
            <div class="open-section">
                <div class="section-title"><i class="bi bi-person-badge"></i> Cliente</div>
                
                <div class="mb-3">
                    <label class="form-label">Seleccionar Cliente <span class="required">*</span></label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Seleccione un cliente...</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">{{ $u->firstname }} {{ $u->lastname }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Información del Ticket -->
            <div class="open-section">
                <div class="section-title"><i class="bi bi-ticket-perforated"></i> Información del Ticket</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Asunto <span class="required">*</span></label>
                        <input type="text" name="subject" class="form-control" placeholder="Describe brevemente el problema" required value="{{ old('subject') }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Departamento: <span class="required">*</span></label>
                        <select name="dept_id" class="form-select" required>
                            <option value="">Seleccione un departamento...</option>
                            @foreach ($departments as $d)
                                <option value="{{ $d->id }}" {{ old('dept_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Prioridad:</label>
                        <select name="priority_id" class="form-select">
                            @foreach ($priorities as $p)
                                <option value="{{ $p->id }}" {{ old('priority_id', 2) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Respuesta inicial -->
            <div class="open-section">
                <div class="section-title"><i class="bi bi-chat-left-text"></i> Respuesta inicial</div>
                <p class="text-muted small mb-2">Describe el problema o deja una nota inicial (opcional).</p>
                <div class="mb-0">
                    <textarea name="body" class="form-control" placeholder="Escribe aquí los detalles del ticket..." rows="5">{{ old('body') }}</textarea>
                </div>
            </div>

            <div class="form-actions mt-4 mb-4">
                <button type="submit" class="btn btn-primary btn-submit" id="btnSubmitTicket"><i class="bi bi-plus-lg"></i> Abrir Ticket</button>
                <a href="{{ route('agent.tickets.index') }}" class="btn btn-outline-secondary ms-2"><i class="bi bi-x-lg"></i> Cancelar</a>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
    /* ── Diseño Profesional Moderno ── */
    @keyframes fadeInUpProfessional {
        0% { opacity: 0; transform: translateY(22px) scale(0.97); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes fadeInHeader {
        0%  { opacity: 0; transform: translateY(-10px); }
        100%{ opacity: 1; transform: translateY(0); }
    }
    .open-ticket-shell { 
        max-width: 880px; 
        margin: 0 auto;
    }
    .open-ticket-shell .tickets-header {
        animation: fadeInHeader 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
        background: radial-gradient(circle at 0% 0%, #ef4444 0%, #1a0000 35%, #000000 100%);
        color: #fff;
        border-radius: 14px;
        padding: 24px 22px;
        margin-bottom: 20px;
        box-shadow: 0 8px 30px rgba(239, 68, 68, 0.2);
    }
    .open-ticket-shell .tickets-header h1 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.01em;
    }
    .open-ticket-shell .tickets-header .sub {
        margin-top: 4px;
        opacity: 0.92;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Tarjetas de Sección */
    .open-section {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 22px 24px;
        margin-bottom: 16px;
        position: relative;
        transition: all 0.3s ease;
    }
    body.dark-mode .open-section {
        background: #000000 !important;
        border-color: #333 !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .open-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #ef4444, #991b1b);
        border-radius: 14px 0 0 14px;
    }
    .open-section .section-title {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #475569;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    body.dark-mode .open-section .section-title {
        color: #cbd5e1;
    }
    .open-section .section-title i {
        color: #ef4444;
        font-size: 1rem;
    }

    .open-section .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.88rem;
        margin-bottom: 6px;
    }
    body.dark-mode .open-section .form-label {
        color: #94a3b8;
    }
    .open-section .form-label .required {
        color: #dc2626;
    }
    .open-section .form-select,
    .open-section .form-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.92rem;
        padding: 10px 14px;
    }
    .open-section .form-select:focus,
    .open-section .form-control:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }
    body.dark-mode .open-section .form-select,
    body.dark-mode .open-section .form-control {
        background: #000 !important;
        border-color: #333 !important;
        color: #fff !important;
    }

    /* Loading state for double-submit prevention */
    .btn-submit.processing {
        pointer-events: none;
        opacity: 0.8;
    }
    .loading-fullscreen-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .loading-fullscreen-card {
        background: #ffffff;
        padding: 28px 40px;
        border-radius: 20px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        border: 1px solid #f1f5f9;
    }
    body.dark-mode .loading-fullscreen-overlay {
        background: rgba(0, 0, 0, 0.6);
    }
    body.dark-mode .loading-fullscreen-card {
        background: #000000;
        border-color: #222;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
    }
    .loading-fullscreen-card .spinner-border {
        color: #ef4444 !important;
    }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('form-open-ticket');
            var btnSubmit = document.getElementById('btnSubmitTicket');

            if (form && btnSubmit) {
                form.addEventListener('submit', function(e) {
                    if (btnSubmit.classList.contains('processing')) {
                        e.preventDefault();
                        return false;
                    }
                    
                    btnSubmit.classList.add('processing');
                    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Creando...';
                    
                    // Crear overlay de carga a pantalla completa
                    var overlay = document.createElement('div');
                    overlay.className = 'loading-fullscreen-overlay';
                    overlay.innerHTML = `
                        <div class="loading-fullscreen-card">
                            <div class="spinner-border text-danger" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <div class="fw-bold mt-2">Creando ticket, por favor espera...</div>
                        </div>
                    `;
                    document.body.appendChild(overlay);
                });
            }
        });
    </script>
    @endpush
</x-agent.layout>
