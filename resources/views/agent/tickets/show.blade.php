<x-agent.layout>
    <div class="ticket-view-container">
        <!-- Header: Title & Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="ticket-page-title m-0 d-flex align-items-center gap-2">
                <i class="bi bi-arrow-clockwise"></i> Ticket #{{ $ticket->ticket_number }}
            </h1>
            <div class="ticket-action-bar d-flex gap-2">
                <a href="{{ route('agent.tickets.index') }}" class="btn-action" title="Volver"><i class="bi bi-arrow-left"></i></a>
                <button class="btn-action" title="Asignar"><i class="bi bi-person-fill-gear"></i></button>
                <button class="btn-action" title="Transferir"><i class="bi bi-arrow-left-right"></i></button>
                <button class="btn-action" title="Imprimir"><i class="bi bi-printer"></i></button>
                <button class="btn-action" title="Ajustes"><i class="bi bi-gear"></i></button>
                <button class="btn-action" title="Email"><i class="bi bi-envelope"></i></button>
            </div>
        </div>

        <!-- Info Grid Panel -->
        <div class="ticket-info-panel mb-4">
            <div class="row g-4">
                <!-- Columna 1 -->
                <div class="col-md-4 ticket-info-col">
                    <div class="info-group">
                        <div class="info-label">ESTADO</div>
                        <div class="info-value">
                            <span class="status-badge status-{{ strtolower($ticket->status->name ?? 'abierto') }}">
                                <i class="bi bi-circle-fill" style="font-size: 0.5rem; margin-right: 4px;"></i>
                                {{ $ticket->status->name ?? 'Abierto' }}
                            </span>
                        </div>
                    </div>
                    <div class="info-group mt-3">
                        <div class="info-label">PRIORIDAD</div>
                        <div class="info-value">
                            <span class="priority-badge priority-{{ strtolower($ticket->priority->name ?? 'baja') }}">
                                <i class="bi bi-bar-chart-fill"></i> {{ $ticket->priority->name ?? 'Baja' }}
                            </span>
                        </div>
                    </div>
                    <div class="info-group mt-3">
                        <div class="info-label"><i class="bi bi-building"></i> DEPARTAMENTO</div>
                        <div class="info-value fw-semibold text-white">{{ $ticket->department->name ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Columna 2 -->
                <div class="col-md-4 ticket-info-col">
                    <div class="info-group">
                        <div class="info-label"><i class="bi bi-person"></i> CLIENTE</div>
                        <div class="info-value fw-bold text-white fs-5">
                            {{ $ticket->user->firstname ?? 'N/A' }} {{ $ticket->user->lastname ?? '' }}
                        </div>
                    </div>
                    <div class="info-group mt-4">
                        <div class="info-label"><i class="bi bi-card-text"></i> TEMA</div>
                        <div class="info-value fw-semibold text-white">{{ $ticket->subject }}</div>
                    </div>
                </div>

                <!-- Columna 3 -->
                <div class="col-md-4 ticket-info-col border-end-0">
                    <div class="info-group">
                        <div class="info-label"><i class="bi bi-person-badge"></i> ASIGNADO A</div>
                        <div class="info-value fw-bold text-white fs-5">
                            {{ $ticket->staff->firstname ?? 'Sin Asignar' }} {{ $ticket->staff->lastname ?? '' }}
                        </div>
                    </div>
                    <div class="info-group mt-4">
                        <div class="info-label"><i class="bi bi-reply-all"></i> ÚLTIMA RESPUESTA</div>
                        <div class="info-value fw-semibold text-white">
                            {{ $ticket->thread && $ticket->thread->entries->count() > 0 ? \Carbon\Carbon::parse($ticket->thread->entries->last()->created)->format('d/m/y h:i A') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="ticket-tabs d-flex border-bottom border-dark mb-4">
            <button class="tab-btn active"><i class="bi bi-chat-left-text"></i> Hilo del Ticket ({{ $ticket->thread ? $ticket->thread->entries->count() : 0 }})</button>
            <button class="tab-btn"><i class="bi bi-box-seam"></i> Inventario (0)</button>
        </div>

        <!-- Thread Messages -->
        <div class="thread-messages">
            @if($ticket->thread && $ticket->thread->entries->count() > 0)
                @foreach($ticket->thread->entries as $entry)
                    @php 
                        $isStaff = $entry->staff_id ? true : false; 
                        // Generar iniciales
                        $authorName = $isStaff ? 
                                     ($ticket->staff->firstname ?? 'Agente') . ' ' . ($ticket->staff->lastname ?? '') : 
                                     ($ticket->user->firstname ?? 'Cliente') . ' ' . ($ticket->user->lastname ?? '');
                        $initials = collect(explode(' ', trim($authorName)))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                    @endphp

                    @if(!$isStaff)
                        <!-- User Message (Right aligned visually) -->
                        <div class="message-wrapper user-message-wrapper d-flex flex-column align-items-end mb-4">
                            <div class="message-author d-flex align-items-center gap-2 mb-2">
                                <span class="author-name text-white fw-bold">{{ $authorName }}</span>
                                <div class="avatar user-avatar">{{ $initials }}</div>
                            </div>
                            <div class="message-bubble user-bubble">
                                <div class="message-date mb-2 text-muted" style="font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($entry->created)->format('d/m/y h:i A') }}
                                </div>
                                <div class="message-content text-white">
                                    {!! nl2br(e($entry->body)) !!}
                                    
                                    @if($entry->attachments && $entry->attachments->count() > 0)
                                        <div class="attachments-list mt-3">
                                            @foreach($entry->attachments as $attachment)
                                                <div class="attachment-card d-inline-flex align-items-center gap-3 bg-black p-2 rounded-3 border border-secondary mt-2 me-2" style="max-width: 320px;">
                                                    <div class="attachment-icon bg-dark text-danger rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
                                                    </div>
                                                    <div class="attachment-info overflow-hidden text-start">
                                                        <div class="text-white fw-bold text-truncate" style="font-size: 0.85rem;" title="{{ $attachment->original_filename }}">
                                                            {{ $attachment->original_filename }}
                                                        </div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">
                                                            {{ number_format($attachment->size / 1024, 0) }} KB
                                                        </div>
                                                    </div>
                                                    <a href="#" class="ms-auto text-secondary hover-white p-2">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Staff Message (Left aligned visually) -->
                        <div class="message-wrapper staff-message-wrapper d-flex flex-column align-items-start mb-4">
                            <div class="message-author d-flex align-items-center gap-2 mb-2 w-100">
                                <div class="avatar staff-avatar">{{ $initials }}</div>
                                <span class="author-name text-white fw-bold">{{ $authorName }}</span>
                                <span class="badge bg-secondary badge-role">Técnico</span>
                                <div class="ms-auto message-actions">
                                    <i class="bi bi-pencil-square text-primary me-2 cursor-pointer"></i>
                                    <i class="bi bi-trash text-danger cursor-pointer"></i>
                                </div>
                            </div>
                            <div class="message-bubble staff-bubble">
                                <div class="message-date mb-2 text-muted" style="font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($entry->created)->format('d/m/y h:i A') }}
                                </div>
                                <div class="message-content text-white">
                                    {!! nl2br(e($entry->body)) !!}
                                    
                                    @if($entry->attachments && $entry->attachments->count() > 0)
                                        <div class="attachments-list mt-3">
                                            @foreach($entry->attachments as $attachment)
                                                <div class="attachment-card d-inline-flex align-items-center gap-3 bg-black p-2 rounded-3 border border-secondary mt-2 me-2" style="max-width: 320px;">
                                                    <div class="attachment-icon bg-dark text-danger rounded p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="bi bi-file-earmark-pdf-fill fs-4"></i>
                                                    </div>
                                                    <div class="attachment-info overflow-hidden text-start">
                                                        <div class="text-white fw-bold text-truncate" style="font-size: 0.85rem;" title="{{ $attachment->original_filename }}">
                                                            {{ $attachment->original_filename }}
                                                        </div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">
                                                            {{ number_format($attachment->size / 1024, 0) }} KB
                                                        </div>
                                                    </div>
                                                    <a href="#" class="ms-auto text-secondary hover-white p-2">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center text-muted my-5 py-5">
                    <i class="bi bi-chat-square-dots fs-1 mb-3 d-block"></i>
                    <p>No hay mensajes en este hilo.</p>
                </div>
            @endif
        </div>

        <!-- Response Form Box -->
        <div class="response-box mt-4">
            <h5 class="response-title">Respuesta</h5>

            @if ($errors->any())
                <div class="alert alert-danger mb-3 p-2 border-0" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; border-radius: 8px;">
                    <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('agent.tickets.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" id="ticket-reply-form">
                @csrf
                <div class="rich-text-container mb-3">
                    <!-- Placeholder for WYSIWYG Toolbar -->
                    <div class="rich-text-toolbar d-flex align-items-center gap-2 px-3 py-2">
                        <button type="button" class="btn-toolbar"><i class="bi bi-magic"></i> <i class="bi bi-caret-down-fill" style="font-size: 0.6rem;"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-justify-left"></i> <i class="bi bi-caret-down-fill" style="font-size: 0.6rem;"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar fw-bold">B</button>
                        <button type="button" class="btn-toolbar fst-italic">I</button>
                        <button type="button" class="btn-toolbar text-decoration-underline">U</button>
                        <button type="button" class="btn-toolbar text-decoration-line-through">S</button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-eraser"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar">sans-serif <i class="bi bi-caret-down-fill" style="font-size: 0.6rem;"></i></button>
                        <button type="button" class="btn-toolbar text-warning fw-bold bg-dark px-2">A <i class="bi bi-caret-down-fill" style="font-size: 0.6rem;"></i></button>
                        <button type="button" class="btn-toolbar">16 <i class="bi bi-caret-down-fill" style="font-size: 0.6rem;"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar"><i class="bi bi-link-45deg"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-image"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-camera-video"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-grid-3x3"></i> <i class="bi bi-caret-down-fill" style="font-size: 0.6rem;"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar"><i class="bi bi-code-slash"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-arrows-fullscreen"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-list-ul"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-list-ol"></i></button>
                        <button type="button" class="btn-toolbar"><i class="bi bi-text-indent-left"></i> <i class="bi bi-caret-down-fill" style="font-size: 0.6rem;"></i></button>
                    </div>
                    <textarea name="response_body" class="form-control rich-text-editor" rows="6" placeholder="" required>{{ old('response_body') }}</textarea>
                </div>

                <div class="dropzone-area mb-4 cursor-pointer" onclick="document.getElementById('file-upload').click()">
                    <div class="dz-icon mx-auto mb-2">
                        <i class="bi bi-paperclip"></i>
                    </div>
                    <p class="mb-1 text-muted" id="upload-text">Arrastra o <span class="text-primary">selecciona archivos</span></p>
                    <small class="text-muted" style="font-size: 0.75rem;">PDF, JPG, PNG, DOC, Vídeo (Máx. 10MB)</small>
                    <input type="file" name="attachments[]" multiple class="d-none" id="file-upload" onchange="updateFileList(this)">
                </div>

                <div class="response-actions">
                    <button type="submit" class="btn btn-respond me-2">
                        <i class="bi bi-send"></i> Responder
                    </button>
                    <button type="button" class="btn btn-close-ticket">
                        <i class="bi bi-check-lg"></i> Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateFileList(input) {
            const textElement = document.getElementById('upload-text');
            if (input.files && input.files.length > 0) {
                const count = input.files.length;
                textElement.innerHTML = `<span class="text-primary fw-bold">${count} archivo(s) seleccionado(s)</span>`;
            } else {
                textElement.innerHTML = `Arrastra o <span class="text-primary">selecciona archivos</span>`;
            }
        }
    </script>
    @endpush

    @push('styles')
    <style>
    /* ── Diseño Idéntico al Original (OsTicket Vigitec Dark) ── */
    
    .ticket-view-container {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 50px;
    }

    .ticket-page-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.5rem;
    }

    .ticket-action-bar .btn-action {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #a0a0a0;
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .ticket-action-bar .btn-action:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* Panel Superior de Información */
    .ticket-info-panel {
        background: #09090b; /* Muy oscuro */
        border: 1px solid #27272a;
        border-radius: 10px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }
    
    .ticket-info-col {
        border-right: 1px solid #27272a;
    }
    @media(max-width: 768px) {
        .ticket-info-col { border-right: none; border-bottom: 1px solid #27272a; padding-bottom: 16px; }
        .ticket-info-col:last-child { border-bottom: none; padding-bottom: 0; }
    }

    .info-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #a1a1aa;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .status-resuelto { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
    .status-abierto { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-cerrado { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        background: rgba(59, 130, 246, 0.1); 
        color: #60a5fa; 
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    
    /* Tabs */
    .ticket-tabs .tab-btn {
        background: transparent;
        border: none;
        color: #a1a1aa;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }
    .ticket-tabs .tab-btn.active {
        color: #fff;
        border-bottom-color: #fff;
    }
    .ticket-tabs .tab-btn:hover:not(.active) {
        color: #d4d4d8;
    }

    /* Burbujas de chat */
    .avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #fff;
    }
    .user-avatar { background: #334155; }
    .staff-avatar { background: #2563eb; }
    
    .badge-role {
        font-size: 0.7rem;
        background: #1e293b !important;
        border: 1px solid #334155;
        padding: 3px 8px;
        border-radius: 12px;
    }

    .message-bubble {
        padding: 16px 20px;
        border-radius: 12px;
        max-width: 80%;
        line-height: 1.5;
        position: relative;
    }
    
    .user-bubble {
        background: #0f172a; /* Azul muy oscuro para cliente */
        border: 1px solid #1e293b;
        border-top-right-radius: 4px;
    }
    
    .staff-bubble {
        background: #18181b; /* Gris muy oscuro para staff */
        border: 1px solid #27272a;
        border-top-left-radius: 4px;
        width: 100%;
        max-width: 80%;
    }
    
    .cursor-pointer { cursor: pointer; }

    /* Respuesta Formulario */
    .response-box {
        background: #09090b;
        border: 1px solid #27272a;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }
    .response-title {
        color: #e2e8f0;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 16px;
    }
    .rich-text-container {
        border: 1px solid #27272a;
        border-radius: 6px;
        overflow: hidden;
    }
    .rich-text-toolbar {
        background: #000;
        border-bottom: 1px solid #27272a;
        overflow-x: auto;
    }
    .rich-text-toolbar::-webkit-scrollbar { height: 4px; }
    .rich-text-toolbar::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
    
    .btn-toolbar {
        background: transparent;
        border: none;
        color: #a1a1aa;
        padding: 4px 8px;
        font-size: 0.9rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-toolbar:hover {
        background: #27272a;
        color: #fff;
    }
    .toolbar-divider {
        width: 1px;
        height: 18px;
        background: #27272a;
        margin: 0 4px;
    }
    .rich-text-editor {
        background: #000 !important;
        border: none !important;
        color: #fff !important;
        resize: vertical;
        padding: 16px;
        border-radius: 0;
        box-shadow: none !important;
    }
    .rich-text-editor:focus {
        background: #000 !important;
        color: #fff !important;
    }
    
    .dropzone-area {
        border: 1px dashed #27272a;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        background: #09090b;
        transition: all 0.2s;
    }
    .dropzone-area:hover {
        border-color: #3b82f6;
        background: #0f172a;
    }
    .dz-icon {
        width: 46px;
        height: 46px;
        background: #e0e7ff;
        color: #4f46e5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
    }
    
    .btn-respond {
        background: #ef4444;
        color: #fff;
        border: none;
        padding: 10px 24px;
        font-weight: 600;
        border-radius: 20px;
        transition: all 0.2s;
    }
    .btn-respond:hover { background: #dc2626; color: #fff; }
    
    .btn-close-ticket {
        background: #3b82f6;
        color: #fff;
        border: none;
        padding: 10px 24px;
        font-weight: 600;
        border-radius: 20px;
        transition: all 0.2s;
    }
    .btn-close-ticket:hover { background: #2563eb; color: #fff; }

    </style>
    @endpush
</x-agent.layout>
