<x-agent.layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-white mb-0">Panel de Control</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Nuevo Ticket
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white h-100" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-3">Tickets Abiertos</h6>
                    <h2 class="mb-0 text-primary">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white h-100" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-3">Tickets Cerrados</h6>
                    <h2 class="mb-0 text-success">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white h-100" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-3">Usuarios Activos</h6>
                    <h2 class="mb-0 text-info">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white h-100" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
                <div class="card-body">
                    <h6 class="card-title text-muted mb-3">Alertas del Sistema</h6>
                    <h2 class="mb-0 text-warning">0</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card text-white" style="background-color: var(--card-bg, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
        <div class="card-header border-bottom-0 pt-4 pb-0" style="background-color: transparent;">
            <h5 class="mb-0">Actividad Reciente</h5>
        </div>
        <div class="card-body">
            <p class="text-muted text-center py-5">
                <i class="bi bi-inbox d-block mb-3" style="font-size: 3rem;"></i>
                No hay actividad reciente para mostrar.
            </p>
        </div>
    </div>
</x-agent.layout>
