        @if($ticket->status_id == 3)
        <!-- Mensaje de Ticket Cerrado -->
        <div class="mt-4 text-center py-5" style="border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; background-color: #0b0f19;">
            <div style="font-size: 1.5rem; color: #94a3b8; margin-bottom: 0.5rem;"><i class="bi bi-lock-fill"></i></div>
            <h5 class="fw-bold mb-2 text-white">Este ticket está cerrado</h5>
            <p class="text-muted mb-4" style="font-size: 0.9rem;">Para escribir una nueva respuesta, primero debes reabrir el ticket.</p>
            <form action="{{ route('agent.tickets.status', $ticket->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status_id" value="1">
                <button type="submit" class="btn btn-danger px-4 py-2 rounded-3" style="font-size: 0.9rem; font-weight: 600; background-color: #f87171; border: none; color: white;">
                    <i class="bi bi-unlock-fill me-2"></i> Reabrir Ticket
                </button>
            </form>
        </div>
        @else
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
                    <div class="rich-text-toolbar d-flex align-items-center gap-1 px-3 py-2">
                        <button type="button" class="btn-toolbar" title="Mágico"><i class="bi bi-magic"></i> <i class="bi bi-caret-down-fill ms-1" style="font-size: 0.5rem; opacity: 0.7;"></i></button>
                        <button type="button" class="btn-toolbar" data-command="justifyLeft" title="Alineación"><i class="bi bi-justify-left"></i> <i class="bi bi-caret-down-fill ms-1" style="font-size: 0.5rem; opacity: 0.7;"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar fw-bold" data-command="bold" title="Negrita" style="font-family: Georgia, serif; font-size: 1.05rem;">B</button>
                        <button type="button" class="btn-toolbar fst-italic" data-command="italic" title="Cursiva" style="font-family: Georgia, serif; font-size: 1.05rem;">I</button>
                        <button type="button" class="btn-toolbar text-decoration-underline" data-command="underline" title="Subrayado" style="font-family: Georgia, serif; font-size: 1.05rem;">U</button>
                        <button type="button" class="btn-toolbar text-decoration-line-through" data-command="strikeThrough" title="Tachado" style="font-family: Georgia, serif; font-size: 1.05rem;">S</button>
                        <button type="button" class="btn-toolbar" data-command="removeFormat" title="Limpiar formato"><i class="bi bi-eraser"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar" style="font-size: 0.85rem;">sans-serif <i class="bi bi-caret-down-fill ms-1" style="font-size: 0.5rem; opacity: 0.7;"></i></button>
                        <button type="button" class="btn-toolbar" title="Color de texto" id="btn-color"><span style="background-color: #fde047; color: #000; padding: 0 4px; font-weight: bold; font-family: serif; border-radius: 2px;">A</span> <i class="bi bi-caret-down-fill ms-1" style="font-size: 0.5rem; opacity: 0.7;"></i></button>
                        <button type="button" class="btn-toolbar" style="font-size: 0.85rem;" id="btn-fontsize">16 <i class="bi bi-caret-down-fill ms-1" style="font-size: 0.5rem; opacity: 0.7;"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar" id="btn-link" title="Insertar enlace"><i class="bi bi-link"></i></button>
                        <button type="button" class="btn-toolbar" id="btn-image" title="Insertar imagen"><i class="bi bi-image"></i></button>
                        <button type="button" class="btn-toolbar" id="btn-video" title="Insertar video"><i class="bi bi-camera-video"></i></button>
                        <button type="button" class="btn-toolbar" id="btn-table" title="Tabla"><i class="bi bi-grid-3x3"></i> <i class="bi bi-caret-down-fill ms-1" style="font-size: 0.5rem; opacity: 0.7;"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar" data-command="insertHorizontalRule" title="Línea horizontal"><i class="bi bi-dash"></i></button>
                        <button type="button" class="btn-toolbar" id="btn-code" title="Código"><i class="bi bi-code-slash"></i></button>
                        <button type="button" class="btn-toolbar" id="btn-fullscreen" title="Pantalla completa"><i class="bi bi-arrows-fullscreen"></i></button>
                        <div class="toolbar-divider"></div>
                        <button type="button" class="btn-toolbar" data-command="insertUnorderedList" title="Lista de viñetas"><i class="bi bi-list-ul"></i></button>
                        <button type="button" class="btn-toolbar" data-command="insertOrderedList" title="Lista numerada"><i class="bi bi-list-ol"></i></button>
                        <button type="button" class="btn-toolbar" data-command="indent" title="Sangría"><i class="bi bi-text-indent-left"></i> <i class="bi bi-caret-down-fill ms-1" style="font-size: 0.5rem; opacity: 0.7;"></i></button>
                    </div>
                    
                    <div id="rich-editor" class="form-control rich-text-editor" contenteditable="true" style="min-height: 150px; overflow-y: auto; outline: none;">{!! old('response_body') !!}</div>
                    <textarea name="response_body" id="hidden-response-body" class="d-none" required>{{ old('response_body') }}</textarea>
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
        @endif
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

            // Lógica del Editor de Texto Enriquecido
            const editor = document.getElementById('rich-editor');
            const hiddenTextarea = document.getElementById('hidden-response-body');
            const form = document.getElementById('ticket-reply-form');
            const toolbarButtons = document.querySelectorAll('.btn-toolbar[data-command]');
            
            // Botones especiales
            const btnLink = document.getElementById('btn-link');
            const btnImage = document.getElementById('btn-image');
            const btnVideo = document.getElementById('btn-video');
            const btnCode = document.getElementById('btn-code');
            const btnFullscreen = document.getElementById('btn-fullscreen');
            const btnColor = document.getElementById('btn-color');
            const btnFontSize = document.getElementById('btn-fontsize');
            const btnTable = document.getElementById('btn-table');

            if (editor) {
                // Ejecutar comandos básicos
                toolbarButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const command = this.getAttribute('data-command');
                        const value = this.getAttribute('data-value') || null;
                        document.execCommand(command, false, value);
                        editor.focus();
                    });
                });

                // Insertar enlace
                if (btnLink) {
                    btnLink.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = prompt('Introduce la URL del enlace:', 'https://');
                        if (url) document.execCommand('createLink', false, url);
                        editor.focus();
                    });
                }
                
                // Insertar Imagen
                if (btnImage) {
                    btnImage.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = prompt('Introduce la URL de la imagen:', 'https://');
                        if (url) document.execCommand('insertImage', false, url);
                        editor.focus();
                    });
                }
                
                // Insertar Video
                if (btnVideo) {
                    btnVideo.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = prompt('Introduce la URL del video de YouTube:');
                        if (url) {
                            // Convertir a embed simple si es posible
                            let embedUrl = url.replace('watch?v=', 'embed/');
                            const html = `<iframe width="560" height="315" src="${embedUrl}" frameborder="0" allowfullscreen></iframe><br>`;
                            document.execCommand('insertHTML', false, html);
                        }
                        editor.focus();
                    });
                }
                
                // Bloque de código
                if (btnCode) {
                    btnCode.addEventListener('click', function(e) {
                        e.preventDefault();
                        const html = `<pre style="background: #111; padding: 10px; border-radius: 5px; color: #0f0;"><code>Escribe tu código aquí...</code></pre><br>`;
                        document.execCommand('insertHTML', false, html);
                        editor.focus();
                    });
                }
                
                // Color de texto
                if (btnColor) {
                    btnColor.addEventListener('click', function(e) {
                        e.preventDefault();
                        const color = prompt('Introduce el color (ej. red, #ff0000):', '#ef4444');
                        if (color) document.execCommand('foreColor', false, color);
                        editor.focus();
                    });
                }
                
                // Tamaño de fuente
                if (btnFontSize) {
                    btnFontSize.addEventListener('click', function(e) {
                        e.preventDefault();
                        const size = prompt('Tamaño (1-7):', '4');
                        if (size >= 1 && size <= 7) document.execCommand('fontSize', false, size);
                        editor.focus();
                    });
                }
                
                // Tabla simple
                if (btnTable) {
                    btnTable.addEventListener('click', function(e) {
                        e.preventDefault();
                        const html = `<table border="1" style="width: 100%; border-collapse: collapse; border-color: #333;"><tr><td>Celda 1</td><td>Celda 2</td></tr><tr><td>Celda 3</td><td>Celda 4</td></tr></table><br>`;
                        document.execCommand('insertHTML', false, html);
                        editor.focus();
                    });
                }
                
                // Pantalla completa
                if (btnFullscreen) {
                    btnFullscreen.addEventListener('click', function(e) {
                        e.preventDefault();
                        const container = document.querySelector('.rich-text-container');
                        if (container.style.position === 'fixed') {
                            container.style.position = 'relative';
                            container.style.top = 'auto';
                            container.style.left = 'auto';
                            container.style.width = 'auto';
                            container.style.height = 'auto';
                            container.style.zIndex = 'auto';
                            editor.style.height = 'auto';
                            editor.style.minHeight = '150px';
                        } else {
                            container.style.position = 'fixed';
                            container.style.top = '0';
                            container.style.left = '0';
                            container.style.width = '100vw';
                            container.style.height = '100vh';
                            container.style.zIndex = '9999';
                            container.style.background = '#000';
                            editor.style.height = 'calc(100vh - 60px)';
                        }
                    });
                }

                // Sincronizar contenido antes de enviar el formulario
                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (editor.innerText.trim() === '') {
                            e.preventDefault();
                            alert('El cuerpo de la respuesta es obligatorio.');
                            return;
                        }
                        hiddenTextarea.value = editor.innerHTML;
                    });
                }
            }
        });
        </script>
    @endpush
