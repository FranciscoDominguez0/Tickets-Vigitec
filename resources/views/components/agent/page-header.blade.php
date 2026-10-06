@props(['title', 'icon' => null, 'actionUrl' => null, 'actionText' => 'Nuevo'])

<div class="tickets-header mb-4" style="margin-top: -8px;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <div class="d-flex align-items-center gap-3">
            @if($icon)
            <div class="d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 52px; height: 52px; background: rgba(255,255,255,0.25); border-radius: 14px; font-size: 1.5rem; flex-shrink: 0;">
                {!! $icon !!}
            </div>
            @endif
            <div>
                <h1>{{ $title }}</h1>
                @if($slot->isNotEmpty())
                <div class="sub">
                    {{ $slot }}
                </div>
                @endif
            </div>
        </div>
        <div class="d-flex gap-2">
            @if(isset($actions))
                {{ $actions }}
            @endif
            @if($actionUrl)
            <a href="{{ $actionUrl }}" class="btn-new"><i class="bi bi-plus-lg me-1"></i> {{ $actionText }}</a>
            @endif
        </div>
    </div>
</div>
