        <!-- Pestañas -->
        <div class="ticket-tabs d-flex border-bottom border-dark mb-4">
            <button class="tab-btn active"><i class="bi bi-chat-left-text"></i> Hilo del Ticket ({{ $ticket->thread ? $ticket->thread->entries->count() : 0 }})</button>
            <button class="tab-btn"><i class="bi bi-box-seam"></i> Inventario (0)</button>
        </div>

        <!-- Mensajes del hilo -->
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
                        <!-- Mensaje del Usuario (Alineado a la derecha visualmente) -->
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
                        <!-- Mensaje del Staff (Alineado a la izquierda visualmente) -->
                        <div class="message-wrapper staff-message-wrapper d-flex flex-column align-items-start mb-4">
                            <div class="message-author d-flex align-items-center gap-2 mb-2 w-100">
                                <div class="avatar staff-avatar">{{ $initials }}</div>
                                <span class="author-name text-white fw-bold">{{ $authorName }}</span>
                                <span class="badge bg-secondary badge-role">Técnico</span>
                                <div class="ms-auto message-actions d-flex align-items-center">
                                    <button type="button" class="btn btn-link p-0 border-0 bg-transparent text-primary me-2" data-bs-toggle="modal" data-bs-target="#editThreadModal{{ $entry->id }}">
                                        <i class="bi bi-pencil-square cursor-pointer"></i>
                                    </button>
                                    <button type="button" class="btn btn-link p-0 border-0 bg-transparent text-danger" data-bs-toggle="modal" data-bs-target="#deleteThreadModal{{ $entry->id }}">
                                        <i class="bi bi-trash cursor-pointer"></i>
                                    </button>

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

                        <!-- Modal de Edición -->
                        <div class="modal fade" id="editThreadModal{{ $entry->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content bg-dark text-white border-secondary">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title">Editar Mensaje</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('agent.tickets.thread.update', $entry->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <textarea name="body" class="form-control bg-black text-white border-secondary" rows="8" required>{{ $entry->body }}</textarea>
                                        </div>
                                        <div class="modal-footer border-secondary">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Modal de Eliminación de Mensaje -->
                        <x-delete-modal 
                            id="deleteThreadModal{{ $entry->id }}" 
                            title="Eliminar Mensaje" 
                            message="¿Estás seguro de eliminar este mensaje? Esta acción no se puede deshacer."
                            formAction="{{ route('agent.tickets.thread.destroy', $entry->id) }}"
                        />
                    @endif
                @endforeach
            @else
                <div class="text-center text-muted my-5 py-5">
                    <i class="bi bi-chat-square-dots fs-1 mb-3 d-block"></i>
                    <p>No hay mensajes en este hilo.</p>
                </div>
            @endif
        </div>
