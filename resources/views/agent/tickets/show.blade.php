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
                    
                    @foreach($staffMembers as $staff)
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
                    
                    @foreach($departments as $dept)
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
                    
                    <x-agent.creative-dropdown-item name="status_id" value="1" :isActive="$ticket->status_id == 1" icon="<i class='bi bi-circle-fill'></i>" iconColor="#3b82f6">
                        Abierto
                    </x-agent.creative-dropdown-item>
                    
                    <x-agent.creative-dropdown-item name="status_id" value="2" :isActive="$ticket->status_id == 2" icon="<i class='bi bi-check-circle-fill'></i>" iconColor="#22c55e">
                        Resuelto
                    </x-agent.creative-dropdown-item>
                    
                    <x-agent.creative-dropdown-item name="status_id" value="3" :isActive="$ticket->status_id == 3" icon="<i class='bi bi-slash-circle-fill'></i>" iconColor="#64748b">
                        Cerrado
                    </x-agent.creative-dropdown-item>
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
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/scp/ticket-view-laravel.css') }}">
    @endpush
</x-agent.layout>
