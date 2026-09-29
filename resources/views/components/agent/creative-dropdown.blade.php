@props(['icon', 'title', 'headerTitle', 'formAction', 'method' => 'POST', 'customClass' => ''])

<div class="custom-dropdown-wrapper" style="position: relative;">
    <button type="button" class="btn-action custom-dropdown-toggle" title="{{ $title }}">
        {!! $icon !!}
    </button>
    <div class="dropdown-menu-custom creative-dropdown-menu custom-dark {{ $customClass }}" style="display: none; position: absolute; right: 0; z-index: 1050;">
        <div class="creative-dropdown-header">{{ $headerTitle }}</div>
        
        @if($formAction)
        <form action="{{ $formAction }}" method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}">
            @if(strtoupper($method) !== 'GET')
                @csrf
            @endif
            @if(in_array(strtoupper($method), ['PUT', 'PATCH', 'DELETE']))
                @method($method)
            @endif
            {{ $slot }}
        </form>
        @else
            {{ $slot }}
        @endif
    </div>
</div>

@once
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.custom-dropdown-toggle').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Cerrar otros
                document.querySelectorAll('.dropdown-menu-custom').forEach(function(menu) {
                    if (menu !== btn.nextElementSibling) {
                        menu.style.display = 'none';
                    }
                });
                // Alternar el actual
                let menu = btn.nextElementSibling;
                if (menu.style.display === 'none' || menu.style.display === '') {
                    menu.style.display = 'block';
                } else {
                    menu.style.display = 'none';
                }
            });
        });

        // Cerrar al hacer clic afuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown-wrapper')) {
                document.querySelectorAll('.dropdown-menu-custom').forEach(function(menu) {
                    menu.style.display = 'none';
                });
            }
        });
    });
</script>
@endpush
@endonce
