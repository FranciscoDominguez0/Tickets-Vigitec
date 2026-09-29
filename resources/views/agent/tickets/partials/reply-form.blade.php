
        <!-- Caja del Formulario de Respuesta -->
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
                    <!-- Barra de Herramientas WYSIWYG -->
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

        document.addEventListener('DOMContentLoaded', function () {
            const dropzone = document.querySelector('.dropzone-area');
            const fileInput = document.getElementById('file-upload');

            if(dropzone) {
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = '#3b82f6';
                    dropzone.style.background = '#0f172a';
                });

                dropzone.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = '';
                    dropzone.style.background = '';
                });

                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.style.borderColor = '';
                    dropzone.style.background = '';
                    
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        updateFileList(fileInput);
                    }
                });
            }
        });
        </script>
    @endpush
