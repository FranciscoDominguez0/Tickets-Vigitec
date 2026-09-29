        <!-- Panel de Información -->
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

