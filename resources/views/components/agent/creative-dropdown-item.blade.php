@props(['name', 'value', 'isActive' => false, 'icon' => null, 'avatar' => null, 'iconColor' => ''])

<button type="submit" name="{{ $name }}" value="{{ $value }}" class="creative-dropdown-item border-0 w-100 text-start {{ $isActive ? 'active' : '' }}">
    @if($avatar)
        <div class="creative-dropdown-avatar">{!! $avatar !!}</div>
    @elseif($icon)
        <div class="creative-dropdown-icon" {!! $iconColor ? 'style="color: '.$iconColor.';"' : '' !!}>{!! $icon !!}</div>
    @endif
    
    <span>{{ $slot }}</span>
    
    @if($isActive)
        <i class="bi bi-check-circle-fill creative-dropdown-check"></i>
    @endif
</button>
