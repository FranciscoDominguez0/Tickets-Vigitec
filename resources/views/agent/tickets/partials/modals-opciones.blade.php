<!-- Modales de Opciones de Ticket -->

<!-- 1. Modal Cambiar Propietario -->
<div class="modal fade" id="cambiarPropietarioModal" tabindex="-1" aria-labelledby="cambiarPropietarioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="cambiarPropietarioModalLabel">
                    <i class="bi bi-person-badge text-primary"></i> Cambiar Propietario del Ticket
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('agent.tickets.change_owner', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="p-3 rounded mb-3" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
                        <div class="small text-secondary mb-1">Propietario actual:</div>
                        <div class="fw-semibold text-white">
                            <i class="bi bi-person me-1"></i> {{ $ticket->user ? $ticket->user->name : 'Sin asignar' }}
                            @if($ticket->user && $ticket->user->email)
                                <span class="text-secondary small ms-1">({{ $ticket->user->email }})</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nuevo_user_id" class="form-label text-white small fw-bold">Nuevo Propietario <span class="text-danger">*</span></label>
                        <select name="user_id" id="nuevo_user_id" class="form-select bg-dark text-white border-secondary" required>
                            <option value="">-- Seleccionar cliente --</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" {{ $ticket->user_id == $usuario->id ? 'disabled' : '' }}>
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="motivo_cambio_propietario" class="form-label text-white small fw-bold">Motivo del Cambio (Opcional)</label>
                        <textarea name="motivo" id="motivo_cambio_propietario" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Ej: Ticket reportado a nombre incorrecto, reasignación por solicitud del cliente..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Guardar Propietario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. Modal Unir Tickets -->
<div class="modal fade" id="unirTicketsModal" tabindex="-1" aria-labelledby="unirTicketsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="unirTicketsModalLabel">
                    <i class="bi bi-link-45deg text-warning"></i> Unir / Fusionar Tickets
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('agent.tickets.merge', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning bg-opacity-10 border-warning text-warning small mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        El ticket secundario seleccionado será fusionado dentro de este ticket principal (<strong>#{{ $ticket->ticket_number }}</strong>) y pasará automáticamente al estado <strong>Cerrado</strong>.
                    </div>

                    <div class="mb-3">
                        <label for="ticket_secundario_id" class="form-label text-white small fw-bold">Ticket a Fusionar (ID o Número) <span class="text-danger">*</span></label>
                        <input type="text" name="ticket_secundario_id" id="ticket_secundario_id" class="form-control bg-dark text-white border-secondary" placeholder="Ej: 14 o #100014" required>
                        <div class="form-text text-secondary small">Indique el identificador o número del ticket duplicado o relacionado.</div>
                    </div>

                    <div class="mb-3">
                        <label for="motivo_fusion" class="form-label text-white small fw-bold">Motivo de la Fusión (Opcional)</label>
                        <textarea name="motivo" id="motivo_fusion" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Ej: Solicitud duplicada del mismo incidente por correo..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-link-45deg me-1"></i> Confirmar Fusión
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 3. Modal Tickets Vinculados -->
<div class="modal fade" id="ticketsVinculadosModal" tabindex="-1" aria-labelledby="ticketsVinculadosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="ticketsVinculadosModalLabel">
                    <i class="bi bi-link text-info"></i> Tickets Vinculados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Listado de vínculos actuales -->
                <div class="mb-4">
                    <h6 class="text-white small fw-bold mb-2">Vínculos Actuales</h6>
                    @if($ticket->vinculos->isEmpty())
                        <div class="text-secondary small p-3 text-center rounded" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                            No hay tickets vinculados actualmente a este ticket.
                        </div>
                    @else
                        <div class="table-responsive rounded border border-secondary">
                            <table class="table table-dark table-sm mb-0 align-middle">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>Ticket</th>
                                        <th>Asunto</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ticket->vinculos as $vinculo)
                                        @php
                                            $vinculado = $vinculo->ticketVinculado;
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('agent.tickets.show', $vinculado->id ?? $vinculo->linked_ticket_id) }}" class="text-info fw-semibold text-decoration-none">
                                                    #{{ $vinculado->ticket_number ?? $vinculo->linked_ticket_id }}
                                                </a>
                                            </td>
                                            <td class="text-truncate" style="max-width: 250px;">
                                                {{ $vinculado->subject ?? 'Ticket #' . $vinculo->linked_ticket_id }}
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: {{ $vinculado->status->color ?? '#64748b' }};">
                                                    {{ $vinculado->status->name ?? 'Abierto' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('agent.tickets.unlink', [$ticket->id, $vinculo->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Desvincular este ticket?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <hr class="border-secondary my-3">

                <!-- Formulario para vincular nuevo ticket -->
                <form action="{{ route('agent.tickets.link', $ticket->id) }}" method="POST">
                    @csrf
                    <h6 class="text-white small fw-bold mb-2">Vincular Nuevo Ticket</h6>
                    <div class="row g-2 align-items-center">
                        <div class="col-8">
                            <input type="text" name="ticket_vinculado_id" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="ID o número de ticket (ej: 12)" required>
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-sm btn-info text-dark w-100 fw-semibold">
                                <i class="bi bi-plus-lg me-1"></i> Vincular Ticket
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. Modal Editar Horas de Soporte -->
<div class="modal fade" id="horasSoporteModal" tabindex="-1" aria-labelledby="horasSoporteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="horasSoporteModalLabel">
                    <i class="bi bi-clock-history text-success"></i> Editar Horas de Soporte
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('agent.tickets.support_hours', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="support_start" class="form-label text-white small fw-bold">Fecha y Hora de Inicio</label>
                        <input type="datetime-local" name="support_start" id="support_start" class="form-control bg-dark text-white border-secondary" value="{{ $ticket->support_start ? $ticket->support_start->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="mb-3">
                        <label for="support_end" class="form-label text-white small fw-bold">Fecha y Hora de Fin</label>
                        <input type="datetime-local" name="support_end" id="support_end" class="form-control bg-dark text-white border-secondary" value="{{ $ticket->support_end ? $ticket->support_end->format('Y-m-d\TH:i') : '' }}">
                    </div>

                    <div class="form-text text-secondary small">
                        Estas horas se reflejan en el acta de reporte y en el registro histórico de atención técnica.
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Guardar Horas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 5. Modal Administrar Referidos -->
<div class="modal fade" id="referidosModal" tabindex="-1" aria-labelledby="referidosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="referidosModalLabel">
                    <i class="bi bi-share text-primary"></i> Administrar Referidos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Referencias actuales -->
                <div class="mb-4">
                    <h6 class="text-white small fw-bold mb-2">Referencias Asignadas</h6>
                    @if($ticket->referidos->isEmpty())
                        <div class="text-secondary small p-3 text-center rounded" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                            No hay miembros ni departamentos referidos a este ticket.
                        </div>
                    @else
                        <div class="table-responsive rounded border border-secondary">
                            <table class="table table-dark table-sm mb-0 align-middle">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>Destino</th>
                                        <th>Tipo</th>
                                        <th>Fecha</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ticket->referidos as $referido)
                                        <tr>
                                            <td>
                                                @if($referido->staff)
                                                    <i class="bi bi-person me-1 text-info"></i> {{ $referido->staff->firstname }} {{ $referido->staff->lastname }}
                                                @elseif($referido->departamento)
                                                    <i class="bi bi-building me-1 text-warning"></i> {{ $referido->departamento->name }}
                                                @else
                                                    Referencia #{{ $referido->id }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $referido->staff_id ? 'Personal' : 'Departamento' }}
                                                </span>
                                            </td>
                                            <td class="text-secondary small">
                                                {{ $referido->created ? $referido->created->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                <form action="{{ route('agent.tickets.unrefer', [$ticket->id, $referido->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Remover esta referencia?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <hr class="border-secondary my-3">

                <!-- Formulario agregar referencia -->
                <form action="{{ route('agent.tickets.refer', $ticket->id) }}" method="POST">
                    @csrf
                    <h6 class="text-white small fw-bold mb-2">Agregar Referencia</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="referido_staff_id" class="form-label text-secondary small">Referir a Miembro de Personal</label>
                            <select name="staff_id" id="referido_staff_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                                <option value="">-- Ninguno --</option>
                                @foreach($miembrosStaff as $personal)
                                    <option value="{{ $personal->id }}">{{ $personal->firstname }} {{ $personal->lastname }} ({{ $personal->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="referido_dept_id" class="form-label text-secondary small">O Referir a Departamento</label>
                            <select name="dept_id" id="referido_dept_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                                <option value="">-- Ninguno --</option>
                                @foreach($departamentos as $depto)
                                    <option value="{{ $depto->id }}">{{ $depto->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Asignar Referencia
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- 6. Modal Solicitud de Inventario / Requisición -->
<div class="modal fade" id="requisicionModal" tabindex="-1" aria-labelledby="requisicionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="requisicionModalLabel">
                    <i class="bi bi-box-seam text-warning"></i> Solicitud de Inventario / Requisición
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('agent.tickets.requisition', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($ticket->requisiciones->isNotEmpty())
                        <div class="mb-4">
                            <h6 class="text-white small fw-bold mb-2">Requisiciones Registradas</h6>
                            <div class="d-flex flex-column gap-2">
                                @foreach($ticket->requisiciones as $req)
                                    <div class="p-2 rounded border border-secondary" style="background: rgba(255,255,255,0.02);">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold text-warning small">Requisición #{{ $req->id }}</span>
                                            <span class="badge {{ $req->status === 'delivered' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $req->status === 'delivered' ? 'Entregada' : 'Pendiente' }}
                                            </span>
                                        </div>
                                        <div class="small text-secondary">
                                            Solicitada por: {{ $req->agente ? $req->agente->firstname : 'Agente' }} | {{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '' }}
                                        </div>
                                        <ul class="small mb-0 mt-1 ps-3 text-white-50">
                                            @foreach($req->items as $item)
                                                <li>{{ $item->product_name }} (Cantidad: {{ $item->quantity }})</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <hr class="border-secondary my-3">
                    @endif

                    <h6 class="text-white small fw-bold mb-2">Nueva Requisición de Materiales</h6>
                    <div id="contenedor_items_requisicion">
                        <div class="row g-2 mb-2 item-requisicion-row">
                            <div class="col-8">
                                <input type="text" name="items[0][product_name]" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Nombre del producto / material" required>
                            </div>
                            <div class="col-3">
                                <input type="number" name="items[0][quantity]" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Cantidad" min="1" value="1" required>
                            </div>
                            <div class="col-1 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-eliminar-item-req" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary mt-1" id="btn_agregar_item_req">
                        <i class="bi bi-plus-lg me-1"></i> Agregar otro artículo
                    </button>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-send me-1"></i> Enviar Requisición
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 7. Modal Gestionar Colaboradores -->
<div class="modal fade" id="colaboradoresModal" tabindex="-1" aria-labelledby="colaboradoresModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="colaboradoresModalLabel">
                    <i class="bi bi-people text-primary"></i> Gestionar Colaboradores
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Lista de colaboradores actuales -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-white small fw-bold mb-0">Colaboradores Asignados</h6>
                        <span class="text-secondary small">
                            Límite: {{ \App\Models\AppSetting::obtener('users.max_collaborators_per_ticket', 10, $ticket->empresa_id) }} máx.
                        </span>
                    </div>

                    @if($ticket->colaboradores->isEmpty())
                        <div class="text-secondary small p-3 text-center rounded" style="background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1);">
                            No hay colaboradores externos asignados a este ticket.
                        </div>
                    @else
                        <div class="table-responsive rounded border border-secondary">
                            <table class="table table-dark table-sm mb-0 align-middle">
                                <thead>
                                    <tr class="text-secondary small">
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th class="text-end">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ticket->colaboradores as $colaborador)
                                        <tr>
                                            <td>{{ $colaborador->name ?: 'Sin nombre' }}</td>
                                            <td><span class="text-info">{{ $colaborador->email }}</span></td>
                                            <td class="text-end">
                                                <form action="{{ route('agent.tickets.collaborators.destroy', [$ticket->id, $colaborador->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Remover este colaborador?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <hr class="border-secondary my-3">

                <!-- Formulario para agregar colaborador -->
                <form action="{{ route('agent.tickets.collaborators.store', $ticket->id) }}" method="POST">
                    @csrf
                    <h6 class="text-white small fw-bold mb-2">Agregar Colaborador</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label for="colab_email" class="form-label text-secondary small">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="colab_email" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="colaborador@empresa.com" required>
                        </div>
                        <div class="col-md-6">
                            <label for="colab_name" class="form-label text-secondary small">Nombre Completo (Opcional)</label>
                            <input type="text" name="name" id="colab_name" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Ej: Juan Pérez">
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Añadir Colaborador
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- 8. Modal Solicitar Revisión Ejecutiva -->
<div class="modal fade" id="revisionEjecutivaModal" tabindex="-1" aria-labelledby="revisionEjecutivaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2" id="revisionEjecutivaModalLabel">
                    <i class="bi bi-shield-lock text-warning"></i> Solicitar Revisión Ejecutiva
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('agent.tickets.request_approval', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($ticket->aprobaciones->isNotEmpty())
                        <div class="mb-3">
                            <h6 class="text-white small fw-bold mb-2">Revisiones Anteriores</h6>
                            @foreach($ticket->aprobaciones as $aprobacion)
                                <div class="p-2 rounded mb-2 border border-secondary" style="background: rgba(255,255,255,0.02);">
                                    <div class="d-flex justify-content-between align-items-center small">
                                        <span>Gerente: <strong>{{ $aprobacion->gerente ? $aprobacion->gerente->firstname . ' ' . $aprobacion->gerente->lastname : 'Supervisor' }}</strong></span>
                                        <span class="badge {{ $aprobacion->status === 'aprobado' ? 'bg-success' : ($aprobacion->status === 'rechazado' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ ucfirst($aprobacion->status) }}
                                        </span>
                                    </div>
                                    <div class="text-secondary small mt-1">
                                        Solicitado el {{ $aprobacion->created_at ? $aprobacion->created_at->format('d/m/Y H:i') : '' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr class="border-secondary my-3">
                    @endif

                    <div class="mb-3">
                        <label for="manager_id" class="form-label text-white small fw-bold">Gerente / Supervisor <span class="text-danger">*</span></label>
                        <select name="manager_id" id="manager_id" class="form-select bg-dark text-white border-secondary" required>
                            <option value="">-- Seleccionar supervisor --</option>
                            @foreach($gerentes as $gerente)
                                <option value="{{ $gerente->id }}">{{ $gerente->firstname }} {{ $gerente->lastname }} ({{ $gerente->role }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="motivo_revision" class="form-label text-white small fw-bold">Motivo de la Revisión <span class="text-danger">*</span></label>
                        <textarea name="motivo" id="motivo_revision" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Indique la justificación para la revisión ejecutiva (ej: Aprobación de presupuesto, excepción de garantía, validación gerencial)..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark fw-semibold">
                        <i class="bi bi-send-check me-1"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 9. Modal Bloquear Email -->
<div class="modal fade" id="bloquearEmailModal" tabindex="-1" aria-labelledby="bloquearEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="background-color: #1e293b; color: #f8fafc;">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title d-flex align-items-center gap-2 text-danger" id="bloquearEmailModalLabel">
                    <i class="bi bi-envelope-x"></i> Bloquear Correo Electrónico
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('agent.tickets.ban_email', $ticket->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger bg-opacity-10 border-danger text-danger small mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Esta acción agregará el correo o dominio a la lista negra del sistema. Los correos entrantes de este remitente serán rechazados automáticamente.
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white small fw-bold">Correo a Bloquear</label>
                        <input type="text" class="form-control bg-dark text-white border-secondary" value="{{ $ticket->user->email ?? 'Sin correo registrado' }}" readonly>
                    </div>

                    @if($ticket->user && $ticket->user->email && str_contains($ticket->user->email, '@'))
                        @php
                            $dominioCliente = substr(strrchr($ticket->user->email, '@'), 1);
                        @endphp
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="bloquear_dominio" value="1" id="bloquear_dominio">
                            <label class="form-check-label text-white small" for="bloquear_dominio">
                                Bloquear todo el dominio (<strong>@<span class="text-danger">{{ $dominioCliente }}</span></strong>)
                            </label>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="motivo_bloqueo" class="form-label text-white small fw-bold">Motivo del Bloqueo (Opcional)</label>
                        <textarea name="motivo" id="motivo_bloqueo" class="form-control bg-dark text-white border-secondary" rows="3" placeholder="Ej: Correo sospechoso de spam, acoso, remitente malicioso..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-slash-circle me-1"></i> Confirmar Bloqueo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let indiceReq = 1;
        const btnAgregarItemReq = document.getElementById('btn_agregar_item_req');
        const contenedorItemsReq = document.getElementById('contenedor_items_requisicion');

        if (btnAgregarItemReq && contenedorItemsReq) {
            btnAgregarItemReq.addEventListener('click', function () {
                const fila = document.createElement('div');
                fila.className = 'row g-2 mb-2 item-requisicion-row';
                fila.innerHTML = `
                    <div class="col-8">
                        <input type="text" name="items[${indiceReq}][product_name]" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Nombre del producto / material" required>
                    </div>
                    <div class="col-3">
                        <input type="number" name="items[${indiceReq}][quantity]" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Cantidad" min="1" value="1" required>
                    </div>
                    <div class="col-1 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 btn-eliminar-item-req">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
                contenedorItemsReq.appendChild(fila);
                indiceReq++;
            });

            contenedorItemsReq.addEventListener('click', function (e) {
                const botonEliminar = e.target.closest('.btn-eliminar-item-req');
                if (botonEliminar && !botonEliminar.disabled) {
                    const fila = botonEliminar.closest('.item-requisicion-row');
                    if (fila) fila.remove();
                }
            });
        }
    });
</script>
@endpush
