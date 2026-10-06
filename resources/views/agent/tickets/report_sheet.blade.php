<x-agent.layout>
    <!-- Top Bar -->
    <div class="mb-4" style="background: radial-gradient(circle at 0% 0%, #ef4444 0%, #1a0000 35%, #000000 100%); border-radius: 14px; padding: 24px 22px; box-shadow: 0 8px 30px rgba(239, 68, 68, 0.2);">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="text-white mb-0" style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.01em;">Facturación</h1>
            <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="btn btn-dark rounded-pill px-3 py-1 d-flex align-items-center" style="background-color: #0b0f19; border: 1px solid rgba(255,255,255,0.15); font-size: 0.85rem; color: rgba(255,255,255,0.8);">
                <i class="bi bi-arrow-left me-2"></i> Volver a Reportes
            </a>
        </div>
    </div>

    <!-- Ticket Details Grid -->
    <div class="card bg-transparent border-0 mb-4" style="border: 1px solid rgba(255,0,0,0.1) !important; border-radius: 8px;">
        <div class="card-body p-4" style="background-color: #000; border-radius: 8px;">
            <div class="row g-0">
                <div class="col-md-4 pe-md-4">
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-info-circle me-1"></i> NÚMERO DE TICKET</span>
                        <h4 class="text-white fw-bold mb-0" style="font-size: 1.2rem;">#{{ $ticket->ticket_number }} <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="text-muted ms-1"><i class="bi bi-box-arrow-up-right" style="font-size: 0.8rem;"></i></a></h4>
                    </div>
                    <div class="my-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);"></div>
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-building me-1"></i> DEPARTAMENTO</span>
                        <span class="text-white fw-semibold" style="font-size: 0.95rem;">{{ $ticket->department->name ?? 'N/A' }}</span>
                    </div>
                    <div class="my-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);"></div>
                    <div>
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-calendar3 me-1"></i> FECHA DE CIERRE</span>
                        <span class="text-white fw-semibold" style="font-size: 0.95rem;">{{ $ticket->closed ? \Carbon\Carbon::parse($ticket->closed)->format('d/m/Y h:i A') : 'No cerrado' }}</span>
                    </div>
                </div>
                
                <div class="col-md-4 px-md-4 border-start border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-person me-1"></i> CLIENTE</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: 1.1rem;">{{ $ticket->user->name ?? 'N/A' }}</h5>
                    </div>
                    <div class="my-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);"></div>
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-bookmark me-1"></i> TEMA (SOPORTE)</span>
                        <span class="text-white fw-semibold d-block text-truncate" style="font-size: 0.95rem;" title="{{ $ticket->subject }}">{{ $ticket->subject }}</span>
                    </div>
                    <div class="my-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);"></div>
                    <div>
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-envelope me-1"></i> EMAIL</span>
                        <span class="text-white fw-semibold" style="font-size: 0.95rem;">{{ $ticket->user->email ?? 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="col-md-4 ps-md-4 border-start border-secondary" style="border-color: rgba(255,255,255,0.05) !important;">
                    <div class="mb-3">
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-person-badge me-1"></i> TÉCNICO ASIGNADO</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: 1.1rem;">{{ $ticket->staff->firstname ?? 'No asignado' }} {{ $ticket->staff->lastname ?? '' }}</h5>
                    </div>
                    <div class="my-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);"></div>
                    <div>
                        <span class="text-muted small d-block mb-1" style="font-size: 0.70rem; letter-spacing: 0.5px; font-weight: 600;"><i class="bi bi-tag me-1"></i> ESTADO DEL REPORTE</span>
                        @if($ticket->report)
                            <span class="badge rounded-pill text-white fw-bold mt-1" style="background-color: #10b981; border: none; padding: 6px 14px; font-size: 0.8rem;">Facturado</span>
                        @else
                            <span class="badge rounded-pill text-white fw-bold mt-1" style="background-color: #6b7280; border: none; padding: 6px 14px; font-size: 0.8rem;">Sin Reporte</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Box -->
    <div class="card border-0 mb-5" style="background-color: #000; border: 1px solid rgba(255,0,0,0.1) !important; border-radius: 8px;">
        <div class="card-header py-3 px-4" style="background-color: #000; border-bottom: 1px solid rgba(255,255,255,0.05);">
            <h6 class="mb-0 fw-bold text-white"><i class="bi bi-hdd-stack me-2"></i> Datos del Reporte</h6>
        </div>
        <div class="card-body p-4" style="background-color: #000;">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(!$ticket->report || request('action') === 'edit')
                <style>
                    .type-card {
                        background-color: #000;
                    }
                    .report-type-radio:checked + .type-card {
                        border-color: #ef4444 !important;
                    }
                    .report-type-radio:checked + .type-card .radio-circle {
                        border-color: #3b82f6 !important;
                        background-color: #3b82f6;
                        box-shadow: inset 0 0 0 3px #000;
                    }
                    .report-type-radio:checked + .type-card .type-title {
                        color: #ef4444 !important;
                    }
                </style>
                <form action="{{ route('agent.tickets.report.store', $ticket->id) }}" method="POST">
                    @csrf
                    
                    <h6 class="fw-bold mb-3" style="color: #3b82f6; font-size: 0.9rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 8px;">
                        <i class="bi bi-table me-1"></i> Detalles del Trabajo
                    </h6>

                    <label class="form-label text-white fw-bold mb-2" style="font-size: 0.95rem;">Trabajos realizados <span class="text-danger">*</span></label>
                    
                    <div class="table-responsive mb-3">
                        <table class="table table-dark table-bordered mb-0" style="background-color: #000; border-color: rgba(255,255,255,0.1);">
                            <thead style="background-color: #000;">
                                <tr>
                                    <th class="py-2 px-3 text-white-50 fw-normal" style="font-size: 0.85rem;">Descripción</th>
                                    <th class="py-2 px-3 text-white-50 fw-normal" style="width: 200px; font-size: 0.85rem;">Precio (USD)</th>
                                    <th class="py-2 px-3 text-center" style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="items-container">
                                @php
                                    $items = old('item_description') 
                                        ? collect(old('item_description'))->map(fn($desc, $i) => ['desc' => $desc, 'price' => old('item_price')[$i]]) 
                                        : ($ticket->report ? $ticket->report->items->map(fn($item) => ['desc' => $item->description, 'price' => $item->price]) : collect([['desc' => '', 'price' => '']]));
                                @endphp
                                
                                @foreach($items as $i => $item)
                                <tr class="item-row">
                                    <td class="p-0">
                                        <input type="text" name="item_description[]" class="form-control bg-black text-white shadow-none border-0 h-100" style="font-size: 0.9rem; min-height: 40px;" placeholder="Ej: Instalación de panel" value="{{ $item['desc'] }}" required>
                                    </td>
                                    <td class="p-0">
                                        <input type="number" name="item_price[]" class="form-control bg-black text-white shadow-none border-0 item-price h-100" style="font-size: 0.9rem; min-height: 40px;" placeholder="0.00" step="0.01" min="0" value="{{ $item['price'] }}" required>
                                    </td>
                                    <td class="p-0 text-center align-middle">
                                        <button type="button" class="btn btn-sm text-danger remove-item p-0"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="py-2 px-3 text-end fw-bold text-white" style="font-size: 0.9rem;">Total:</td>
                                    <td class="py-2 px-3 fw-bold text-white" id="total-display" colspan="2" style="font-size: 0.9rem;">$0.00</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <button type="button" id="add-item" class="btn w-100 mb-4 rounded-2" style="background-color: #000; border: 1px solid rgba(239, 68, 68, 0.4); color: #ef4444; font-size: 0.85rem; padding: 6px;">
                        <i class="bi bi-plus-circle me-1"></i> Agregar ítem
                    </button>

                    <div class="mb-4 mt-2">
                        <label class="form-label text-white fw-bold mb-2" style="font-size: 0.95rem;">Observaciones <span class="text-muted fw-normal" style="font-size: 0.85rem;">(Opcional)</span></label>
                        <textarea name="observations" class="form-control text-white shadow-none" style="background-color: #000; border: 1px solid rgba(255,255,255,0.1); font-size: 0.9rem;" rows="3" placeholder="Cualquier nota extra relevante...">{{ old('observations', $ticket->report->observations ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white fw-bold mb-3" style="font-size: 0.95rem;">Acción / Tipo de Reporte</label>
                        <div class="row g-3">
                            @php $currType = old('report_type', $ticket->report->billing_status ?? 'pending'); @endphp
                            
                            <div class="col-md-4">
                                <label class="w-100 h-100 mb-0">
                                    <input type="radio" name="report_type" value="pending" class="d-none report-type-radio" {{ $currType === 'pending' ? 'checked' : '' }}>
                                    <div class="p-3 rounded-3 type-card" style="border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: all 0.2s;">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="radio-circle me-2" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4);"></div>
                                            <span class="fw-bold type-title" style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">Pendiente Facturación</span>
                                        </div>
                                        <div class="text-muted ms-4" style="font-size: 0.8rem; line-height: 1.2;">Se marcará para facturar después.</div>
                                    </div>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="w-100 h-100 mb-0">
                                    <input type="radio" name="report_type" value="visita_tecnica" class="d-none report-type-radio" {{ $currType === 'visita_tecnica' ? 'checked' : '' }}>
                                    <div class="p-3 rounded-3 type-card" style="border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: all 0.2s;">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="radio-circle me-2" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4);"></div>
                                            <span class="fw-bold type-title" style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">Visita Técnica</span>
                                        </div>
                                        <div class="text-muted ms-4" style="font-size: 0.8rem; line-height: 1.2;">Reportar como una visita sin factura pendiente.</div>
                                    </div>
                                </label>
                            </div>

                            <div class="col-md-4">
                                <label class="w-100 h-100 mb-0">
                                    <input type="radio" name="report_type" value="cotizacion" class="d-none report-type-radio" {{ $currType === 'cotizacion' ? 'checked' : '' }}>
                                    <div class="p-3 rounded-3 type-card" style="border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: all 0.2s;">
                                        <div class="d-flex align-items-center mb-1">
                                            <div class="radio-circle me-2" style="width: 14px; height: 14px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.4);"></div>
                                            <span class="fw-bold type-title" style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">Cotización</span>
                                        </div>
                                        <div class="text-muted ms-4" style="font-size: 0.8rem; line-height: 1.2;">Reportar como una cotización realizada.</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.05);">
                        <button type="submit" class="btn btn-danger px-4 py-2 rounded-2" style="font-weight: 500; font-size: 0.95rem;">
                            <i class="bi bi-check-square me-1"></i> Guardar Reporte
                        </button>
                    </div>
                </form>
            @else
                <p class="text-muted mb-4" style="font-size: 0.9rem;"><i class="bi bi-info-circle opacity-75 me-1"></i> Este ticket ya tiene un reporte generado. Puedes editarlo o descargar el PDF.</p>
                
                <h6 class="mb-3 fw-bold" style="color: #60a5fa !important; font-size: 0.95rem;"><i class="bi bi-layout-text-window-reverse me-1"></i> Detalle de Trabajos Realizados</h6>
                
                <div class="table-responsive mb-4">
                    <table class="table table-dark table-borderless text-white mb-0" style="background-color: transparent; border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden;">
                        <thead style="border-bottom: 1px solid rgba(255,255,255,0.1); background-color: #000;">
                            <tr>
                                <th class="py-3 px-4 fw-bold">Descripción</th>
                                <th class="text-end py-3 px-4 fw-bold" style="width: 160px;">Precio</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: #000;">
                            @foreach($ticket->report->items as $item)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td class="py-3 px-4">{{ $item->description }}</td>
                                    <td class="text-end py-3 px-4">${{ number_format($item->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background-color: #000;">
                            <tr>
                                <td class="text-end fw-bold py-3 px-4">Total:</td>
                                <td class="text-end fw-bold text-white py-3 px-4">${{ number_format($ticket->report->final_price, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('agent.tickets.report_sheet', ['id' => $ticket->id, 'action' => 'edit']) }}" class="btn btn-outline-primary rounded-pill px-4" style="border-color: #3b82f6; color: #3b82f6; font-weight: 500;">
                        <i class="bi bi-pencil-square me-1"></i> Editar Reporte
                    </a>
                    <!-- NOTE: assuming a route to download exists, otherwise placeholder -->
                    <button type="button" class="btn btn-danger rounded-pill px-4" style="background-color: #ef4444; border: none; font-weight: 500;">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Descargar PDF
                    </button>
                </div>
            @endif
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('items-container');
            const totalDisplay = document.getElementById('total-display');
            
            function updateTotal() {
                if(!totalDisplay) return;
                let total = 0;
                document.querySelectorAll('.item-price').forEach(input => {
                    const val = parseFloat(input.value) || 0;
                    total += val;
                });
                totalDisplay.textContent = '$' + total.toFixed(2);
            }
            
            if(container) {
                container.addEventListener('input', function(e) {
                    if (e.target.classList.contains('item-price')) {
                        updateTotal();
                    }
                });
                
                container.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-item')) {
                        e.target.closest('.item-row').remove();
                        updateTotal();
                    }
                });
                
                const btnAdd = document.getElementById('add-item');
                if(btnAdd) {
                    btnAdd.addEventListener('click', function() {
                        const template = `
                                <tr class="item-row">
                                    <td class="p-0">
                                        <input type="text" name="item_description[]" class="form-control bg-black text-white shadow-none border-0 h-100" style="font-size: 0.9rem; min-height: 40px;" placeholder="Ej: Instalación de panel" required>
                                    </td>
                                    <td class="p-0">
                                        <input type="number" name="item_price[]" class="form-control bg-black text-white shadow-none border-0 item-price h-100" style="font-size: 0.9rem; min-height: 40px;" placeholder="0.00" step="0.01" min="0" required>
                                    </td>
                                    <td class="p-0 text-center align-middle">
                                        <button type="button" class="btn btn-sm text-danger remove-item p-0"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                        `;
                        container.insertAdjacentHTML('beforeend', template);
                    });
                }
                
                updateTotal();
            }
        });
    </script>
    @endpush
</x-agent.layout>