<x-agent.admin-layout>
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom" style="border-color: rgba(255, 255, 255, 0.08) !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; font-size: 1.4rem;">
                <i class="bi {{ $icono ?? 'bi-folder2-open' }}"></i>
            </div>
            <div>
                <h4 class="text-white fw-bold mb-0">{{ $titulo }}</h4>
            </div>
        </div>
        <div>
            <span class="badge px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1" style="background: rgba(148, 163, 184, 0.1); border: 1px solid rgba(148, 163, 184, 0.2); color: #94a3b8; font-size: 0.78rem;">
                <i class="bi bi-clock-history"></i> En desarrollo
            </span>
        </div>
    </div>

    <!-- Contenedor en blanco para desarrollo paso a paso -->
    <div class="card border-0 shadow-sm" style="background-color: #16191d; border: 1px solid rgba(255, 255, 255, 0.08) !important; border-radius: 14px; min-height: 420px;">
        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-5">
            <div class="mb-3 d-flex align-items-center justify-content-center rounded-circle" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06); width: 84px; height: 84px;">
                <i class="bi {{ $icono ?? 'bi-file-earmark-code' }}" style="font-size: 2.4rem; color: #64748b;"></i>
            </div>
            <h5 class="text-white fw-bold mb-2">{{ $titulo }}</h5>
            <p class="text-secondary mb-3" style="max-width: 520px; font-size: 0.92rem; line-height: 1.6;">
                {{ $descripcion ?? 'Esta sección ha sido configurada y está lista para su desarrollo paso a paso.' }}
            </p>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark border border-secondary border-opacity-25 text-secondary px-3 py-2" style="font-family: monospace; font-size: 0.8rem;">
                    {{ request()->path() }}
                </span>
            </div>
        </div>
    </div>
</x-agent.admin-layout>

