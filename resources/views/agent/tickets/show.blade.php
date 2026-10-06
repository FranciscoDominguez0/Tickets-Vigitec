<x-agent.layout>
    <div class="ticket-view-container">
        <!-- Encabezado: Título y Acciones -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="ticket-page-title m-0 d-flex align-items-center gap-2">
                <i class="bi bi-arrow-clockwise"></i> Ticket #{{ $ticket->ticket_number }}
            </h1>
            <div class="ticket-action-bar d-flex gap-2">
                <a href="{{ route('agent.tickets.index') }}" class="btn-action" title="Volver"><i class="bi bi-arrow-left"></i></a>
                
                <!-- Dropdown Asignar -->
                <x-agent.creative-dropdown 
                    icon="<i class='bi bi-person-fill-gear'></i>" 
                    title="Asignar" 
                    headerTitle="Asignar Agente" 
                    formAction="{{ route('agent.tickets.assign', $ticket->id) }}">
                    
                    <x-agent.creative-dropdown-item name="staff_id" value="0" :isActive="empty($ticket->staff_id)" avatar="<i class='bi bi-person-dash'></i>">
                        — Sin asignar —
                    </x-agent.creative-dropdown-item>
                    
                    @foreach($miembrosStaff as $staff)
                        @php
                            $initials = strtoupper(substr($staff->firstname, 0, 1) . substr($staff->lastname, 0, 1));
                        @endphp
                        <x-agent.creative-dropdown-item name="staff_id" value="{{ $staff->id }}" :isActive="$ticket->staff_id == $staff->id" :avatar="$initials">
                            {{ $staff->firstname }} {{ $staff->lastname }}
                        </x-agent.creative-dropdown-item>
                    @endforeach
                </x-agent.creative-dropdown>

                <!-- Dropdown Transferir -->
                <x-agent.creative-dropdown 
                    icon="<i class='bi bi-arrow-left-right'></i>" 
                    title="Transferir" 
                    headerTitle="Transferir Departamento" 
                    formAction="{{ route('agent.tickets.transfer', $ticket->id) }}">
                    
                    @foreach($departamentos as $dept)
                        <x-agent.creative-dropdown-item name="dept_id" value="{{ $dept->id }}" :isActive="$ticket->dept_id == $dept->id" icon="<i class='bi bi-building'></i>">
                            {{ $dept->name }}
                        </x-agent.creative-dropdown-item>
                    @endforeach
                </x-agent.creative-dropdown>

                <button type="button" class="btn-action" title="Imprimir" onclick="window.print()"><i class="bi bi-printer"></i></button>

                <!-- Dropdown Estado -->
                <x-agent.creative-dropdown 
                    icon="<i class='bi bi-check2-circle'></i>" 
                    title="Cambiar Estado" 
                    headerTitle="Cambiar Estado" 
                    formAction="{{ route('agent.tickets.status', $ticket->id) }}">
                    
                    @foreach($estados as $estado)
                        @php
                            $iconClass = 'bi-circle-fill';
                            $iconColor = '#64748b'; // default gris
                            if(strtolower($estado->name) == 'abierto') { $iconColor = '#3b82f6'; }
                            if(strtolower($estado->name) == 'resuelto' || strtolower($estado->name) == 'cerrado') { $iconColor = '#22c55e'; $iconClass = 'bi-check-circle-fill'; }
                            if(strtolower($estado->name) == 'en camino') { $iconColor = '#f59e0b'; $iconClass = 'bi-truck'; }
                            if(strtolower($estado->name) == 'en proceso') { $iconColor = '#eab308'; $iconClass = 'bi-gear-fill'; }
                        @endphp
                        @if(strtolower($estado->name) == 'cerrado')
                            <button type="button" class="creative-dropdown-item border-0 w-100 text-start {{ $ticket->status_id == $estado->id ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#closeTicketModal">
                                <div class="creative-dropdown-icon" style="color: {{ $iconColor }};"><i class="bi {{ $iconClass }}"></i></div>
                                <span>{{ $estado->name }}</span>
                                @if($ticket->status_id == $estado->id)
                                    <i class="bi bi-check-circle-fill creative-dropdown-check"></i>
                                @endif
                            </button>
                        @else
                            <x-agent.creative-dropdown-item name="status_id" value="{{ $estado->id }}" :isActive="$ticket->status_id == $estado->id" icon="<i class='bi {{ $iconClass }}'></i>" iconColor="{{ $iconColor }}">
                                {{ $estado->name }}
                            </x-agent.creative-dropdown-item>
                        @endif
                    @endforeach
                </x-agent.creative-dropdown>

                <!-- Dropdown Opciones -->
                <x-agent.creative-dropdown 
                    icon="<i class='bi bi-gear'></i>" 
                    title="Opciones de Ticket" 
                    headerTitle="Opciones de Ticket" 
                    formAction="">
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-person-badge"></i></div>
                        <span>Cambiar Propietario</span>
                    </a>
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-link-45deg"></i></div>
                        <span>Unir Tiquetes</span>
                    </a>
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-link"></i></div>
                        <span>Tickets vinculados</span>
                    </a>
                    
                    <div style="height: 1px; background: rgba(255,255,255,0.05); margin: 6px 0;"></div>
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-clock-history"></i></div>
                        <span>Editar horas de soporte</span>
                    </a>
                    
                    <div style="height: 1px; background: rgba(255,255,255,0.05); margin: 6px 0;"></div>
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-share"></i></div>
                        <span>Administrar referidos</span>
                    </a>
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-box-seam"></i></div>
                        <span>Solicitud de Inventario</span>
                    </a>
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-people"></i></div>
                        <span>Gestionar Colaboradores</span>
                    </a>
                    
                    <div style="height: 1px; background: rgba(255,255,255,0.05); margin: 6px 0;"></div>
                    
                    <a href="#" class="creative-dropdown-item border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon"><i class="bi bi-shield-lock"></i></div>
                        <span>Solicitar revisión ejecutiva</span>
                    </a>
                    
                    <a href="#" class="creative-dropdown-item text-danger border-0 w-100 text-start text-decoration-none">
                        <div class="creative-dropdown-icon text-danger" style="background: rgba(239, 68, 68, 0.1);"><i class="bi bi-envelope-x"></i></div>
                        <span>Bloquear Email</span>
                    </a>
                    
                    <button type="button" class="creative-dropdown-item text-danger border-0 w-100 text-start bg-transparent" data-bs-toggle="modal" data-bs-target="#deleteTicketModal">
                        <div class="creative-dropdown-icon text-danger" style="background: rgba(239, 68, 68, 0.1);"><i class="bi bi-trash"></i></div>
                        <span>Borrar Ticket</span>
                    </button>
                </x-agent.creative-dropdown>
            </div>
        </div>

        <!-- Panel de Información -->
        @include('agent.tickets.partials.info-grid')

        <!-- Tabs y Hilo de Mensajes -->
        @include('agent.tickets.partials.thread')

        <!-- Formulario de Respuesta -->
        @include('agent.tickets.partials.reply-form')
        
        <!-- Modal de Eliminación -->
        <x-delete-modal 
            id="deleteTicketModal" 
            title="Eliminar Ticket #{{ $ticket->ticket_number }}" 
            message="¿Estás seguro de que deseas eliminar este ticket de forma permanente? Esta acción no se puede deshacer y se perderán todos los mensajes del hilo."
            formAction="{{ route('agent.tickets.destroy', $ticket->id) }}"
        />
        <!-- Modal de Cierre de Ticket -->
        <div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="closeTicketModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
                    <div class="modal-header border-bottom border-secondary">
                        <h5 class="modal-title" id="closeTicketModalLabel">¿Cómo deseas cerrar este ticket?</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted mb-4">Selecciona una de las siguientes opciones para proceder con el cierre del ticket #{{ $ticket->ticket_number }}.</p>
                        
                        <div class="d-grid gap-3">
                            <!-- Opción 1: Cerrar sin firma -->
                            <form action="{{ route('agent.tickets.status', $ticket->id) }}" method="POST">
                                @csrf
                                <!-- Buscamos el ID del estado 'Cerrado' dinámicamente -->
                                @php
                                    $cerradoStateId = $estados->firstWhere('name', 'Cerrado')->id ?? 3;
                                @endphp
                                <input type="hidden" name="status_id" value="{{ $cerradoStateId }}">
                                <button type="submit" class="btn btn-outline-light w-100 text-start p-3 rounded-3 d-flex align-items-center gap-3" style="border-color: rgba(255,255,255,0.1);">
                                    <div style="background-color: rgba(34, 197, 94, 0.1); color: #22c55e; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                        <i class="bi bi-file-earmark-check"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold mb-1">Cerrar sin firma</div>
                                        <div class="small text-muted" style="white-space: normal;">El ticket se cerrará sin conformidad y pasarás a llenar la hoja de reporte.</div>
                                    </div>
                                </button>
                            </form>

                            <!-- Opción 2: Cerrar con firma -->
                            <button type="button" class="btn btn-outline-light w-100 text-start p-3 rounded-3 d-flex align-items-center gap-3" style="border-color: rgba(255,255,255,0.1);" data-bs-toggle="modal" data-bs-target="#modalFirmaTicket" data-bs-dismiss="modal">
                                <div style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                    <i class="bi bi-pen"></i>
                                </div>
                                <div>
                                    <div class="fw-bold mb-1">Cerrar con firma en pantalla</div>
                                    <div class="small text-muted" style="white-space: normal;">Se abrirá un lienzo para que el cliente firme directamente en el dispositivo.</div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Firma en Pantalla -->
        <div class="modal fade" id="modalFirmaTicket" tabindex="-1" aria-labelledby="modalFirmaTicketLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
                    <div class="modal-header border-bottom border-secondary">
                        <h5 class="modal-title" id="modalFirmaTicketLabel">Firma de Conformidad</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p class="text-muted mb-3">Por favor, dibuje su firma en el cuadro inferior para proceder con el cierre del ticket.</p>
                        
                        <div class="bg-white rounded p-2 mb-3" style="border: 2px dashed #94a3b8;">
                            <canvas id="lienzoFirma" width="400" height="200" style="touch-action: none; cursor: crosshair; max-width: 100%;"></canvas>
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnLimpiarFirma">Limpiar Firma</button>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#closeTicketModal">Volver</button>
                        
                        <form action="{{ route('agent.tickets.request_signature', $ticket->id) }}" method="POST" id="formGuardarFirma">
                            @csrf
                            <input type="hidden" name="firma_base64" id="inputFirmaBase64">
                            <!-- También enviamos el estado 'Cerrado' para que el ticket se cierre -->
                            @php
                                $cerradoStateId = $estados->firstWhere('name', 'Cerrado')->id ?? 3;
                            @endphp
                            <input type="hidden" name="status_id" value="{{ $cerradoStateId }}">
                            <button type="button" class="btn btn-primary" id="btnGuardarFirma">Guardar y Cerrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/scp/ticket-view-laravel.css') }}">
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lienzo = document.getElementById('lienzoFirma');
            if (!lienzo) return;

            const contexto = lienzo.getContext('2d');
            let estaDibujando = false;
            let haDibujado = false;

            // Ajustar el canvas de ser necesario
            contexto.lineWidth = 3;
            contexto.lineCap = 'round';
            contexto.strokeStyle = '#000000';

            // Para manejar correctamente la posición en el canvas
            function obtenerPosicion(evento) {
                const rect = lienzo.getBoundingClientRect();
                const clientX = evento.clientX || (evento.touches && evento.touches[0].clientX);
                const clientY = evento.clientY || (evento.touches && evento.touches[0].clientY);
                
                return {
                    x: clientX - rect.left,
                    y: clientY - rect.top
                };
            }

            function iniciarDibujo(evento) {
                evento.preventDefault();
                estaDibujando = true;
                haDibujado = true;
                const pos = obtenerPosicion(evento);
                contexto.beginPath();
                contexto.moveTo(pos.x, pos.y);
            }

            function dibujar(evento) {
                if (!estaDibujando) return;
                evento.preventDefault();
                const pos = obtenerPosicion(evento);
                contexto.lineTo(pos.x, pos.y);
                contexto.stroke();
            }

            function detenerDibujo() {
                estaDibujando = false;
                contexto.closePath();
            }

            // Eventos para mouse
            lienzo.addEventListener('mousedown', iniciarDibujo);
            lienzo.addEventListener('mousemove', dibujar);
            lienzo.addEventListener('mouseup', detenerDibujo);
            lienzo.addEventListener('mouseleave', detenerDibujo);

            // Eventos para touch
            lienzo.addEventListener('touchstart', iniciarDibujo, { passive: false });
            lienzo.addEventListener('touchmove', dibujar, { passive: false });
            lienzo.addEventListener('touchend', detenerDibujo);

            // Limpiar firma
            document.getElementById('btnLimpiarFirma').addEventListener('click', function () {
                contexto.clearRect(0, 0, lienzo.width, lienzo.height);
                haDibujado = false;
            });

            // Guardar firma
            document.getElementById('btnGuardarFirma').addEventListener('click', function () {
                if (!haDibujado) {
                    alert('Por favor dibuje la firma del cliente antes de cerrar.');
                    return;
                }

                // Extraer base64
                const imagenBase64 = lienzo.toDataURL('image/png');
                document.getElementById('inputFirmaBase64').value = imagenBase64;
                
                // Enviar formulario
                document.getElementById('formGuardarFirma').submit();
            });

            // Reajustar canvas al abrir modal por si las dimensiones cambian (opcional)
            const modalFirma = document.getElementById('modalFirmaTicket');
            modalFirma.addEventListener('shown.bs.modal', function () {
                // Se podría reajustar si el modal cambia de tamaño
            });
        });
    </script>
    @endpush
</x-agent.layout>
