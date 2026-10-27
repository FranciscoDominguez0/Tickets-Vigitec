@props([
    'id' => 'modalCrearUsuario',
    'titulo' => 'Registrar Nuevo Usuario',
    'accion' => route('agent.users.store'),
    'organizaciones' => null,
    'modoAjax' => false,
])

<x-agent.modal-usuario 
    :id="$id"
    :titulo="$titulo"
    :accion="$accion"
    :organizaciones="$organizaciones"
    :modoAjax="$modoAjax"
/>
