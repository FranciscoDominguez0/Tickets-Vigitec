@props([
    'id',
    'titulo' => null,
    'title' => null,
    'subtitulo' => null,
    'icono' => null,
    'colorIcono' => 'text-danger',
    'accion' => '',
    'formAction' => '',
    'metodo' => 'POST',
    'method' => 'POST',
    'textoBoton' => 'Guardar',
    'submitText' => null,
    'iconoBoton' => 'bi-save',
    'colorBoton' => 'btn-danger',
    'tamano' => 'modal-lg',
    'centrado' => true,
    'estatico' => true,
    'modoAjax' => false,
])

<x-modal 
    :id="$id"
    :titulo="$titulo ?? $title"
    :subtitulo="$subtitulo"
    :icono="$icono"
    :colorIcono="$colorIcono"
    :accion="$accion ?: $formAction"
    :metodo="$metodo !== 'POST' ? $metodo : $method"
    :textoBoton="$submitText ?? $textoBoton"
    :iconoBoton="$iconoBoton"
    :colorBoton="$colorBoton"
    :tamano="$tamano"
    :centrado="$centrado"
    :estatico="$estatico"
    :modoAjax="$modoAjax"
>
    {{ $slot }}

    @isset($footer)
        <x-slot:footer>
            {{ $footer }}
        </x-slot:footer>
    @endisset
</x-modal>
