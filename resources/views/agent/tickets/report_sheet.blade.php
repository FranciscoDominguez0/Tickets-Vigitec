<x-agent.layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-white mb-0">Hoja de reporte de tickets</h1>
        <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left"></i> Volver al Ticket
        </a>
    </div>

    <div class="card text-white" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
        <div class="card-header border-secondary-subtle py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Ticket #{{ $ticket->ticket_number }}</h5>
                <span class="badge bg-secondary">Cerrado</span>
            </div>
            <div class="small text-muted mt-1">{{ $ticket->subject }}</div>
        </div>
        <div class="card-body">
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
                <form action="{{ route('agent.tickets.report.store', $ticket->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold">Tipo de Reporte</label>
                        <select name="report_type" class="form-select bg-dark text-white border-secondary">
                            <option value="pending" {{ old('report_type', $ticket->report->billing_status ?? '') === 'pending' ? 'selected' : '' }}>Pendiente (Normal)</option>
                            <option value="visita_tecnica" {{ old('report_type', $ticket->report->billing_status ?? '') === 'visita_tecnica' ? 'selected' : '' }}>Visita Técnica</option>
                            <option value="cotizacion" {{ old('report_type', $ticket->report->billing_status ?? '') === 'cotizacion' ? 'selected' : '' }}>Cotización</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold">Observaciones (Opcional)</label>
                        <textarea name="observations" class="form-control bg-dark text-white border-secondary" rows="3">{{ old('observations', $ticket->report->observations ?? '') }}</textarea>
                    </div>

                    <h6 class="mb-3 text-danger border-bottom border-secondary-subtle pb-2"><i class="bi bi-tools"></i> Detalle de Trabajos Realizados</h6>
                    
                    <div id="items-container">
                        @php
                            $items = old('item_description') 
                                ? collect(old('item_description'))->map(fn($desc, $i) => ['desc' => $desc, 'price' => old('item_price')[$i]]) 
                                : ($ticket->report ? $ticket->report->items->map(fn($item) => ['desc' => $item->description, 'price' => $item->price]) : collect([['desc' => '', 'price' => '']]));
                        @endphp
                        
                        @foreach($items as $i => $item)
                            <div class="row g-2 mb-2 item-row">
                                <div class="col-8 col-md-9">
                                    <input type="text" name="item_description[]" class="form-control bg-dark text-white border-secondary" placeholder="Descripción del trabajo" value="{{ $item['desc'] }}" required>
                                </div>
                                <div class="col-3 col-md-2">
                                    <input type="number" name="item_price[]" class="form-control bg-dark text-white border-secondary item-price" placeholder="0.00" step="0.01" min="0" value="{{ $item['price'] }}" required>
                                </div>
                                <div class="col-1 text-end">
                                    @if($i > 0)
                                        <button type="button" class="btn btn-outline-danger btn-sm mt-1 remove-item"><i class="bi bi-trash"></i></button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <button type="button" id="add-item" class="btn btn-outline-secondary btn-sm mt-2"><i class="bi bi-plus-circle"></i> Agregar Ítem</button>

                    <div class="d-flex justify-content-end align-items-center mt-4 pt-3 border-top border-secondary-subtle">
                        <div class="me-4 text-end">
                            <span class="text-muted small d-block">Total Calculado</span>
                            <span class="fs-4 fw-bold text-success" id="total-display">$0.00</span>
                        </div>
                        <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="bi bi-save"></i> Guardar Reporte</button>
                    </div>
                </form>
            @else
                <div class="alert alert-secondary text-dark mb-4">
                    <i class="bi bi-info-circle me-1"></i> Este ticket ya tiene un reporte generado. Puedes editarlo.
                </div>

                @if($ticket->report->observations)
                    <div class="mb-4 p-3 rounded" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                        <strong class="d-block text-muted mb-1">Observaciones:</strong>
                        {!! nl2br(e($ticket->report->observations)) !!}
                    </div>
                @endif

                <h6 class="mb-3 text-danger border-bottom border-secondary-subtle pb-2"><i class="bi bi-card-checklist me-1"></i> Detalle de Trabajos Realizados</h6>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered border-secondary text-white">
                        <thead style="background: rgba(255,255,255,0.05);">
                            <tr>
                                <th>Descripción</th>
                                <th class="text-end" style="width: 160px;">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ticket->report->items as $item)
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: rgba(239,68,68,0.1);">
                                <td class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold text-success fs-5">${{ number_format($ticket->report->final_price, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top border-secondary-subtle">
                    <a href="{{ route('agent.tickets.report_sheet', ['id' => $ticket->id, 'action' => 'edit']) }}" class="btn btn-outline-primary px-4 rounded-pill">
                        <i class="bi bi-pencil-square"></i> Editar Reporte
                    </a>
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
                            <div class="row g-2 mb-2 item-row">
                                <div class="col-8 col-md-9">
                                    <input type="text" name="item_description[]" class="form-control bg-dark text-white border-secondary" placeholder="Descripción del trabajo" required>
                                </div>
                                <div class="col-3 col-md-2">
                                    <input type="number" name="item_price[]" class="form-control bg-dark text-white border-secondary item-price" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                                <div class="col-1 text-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-1 remove-item"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
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