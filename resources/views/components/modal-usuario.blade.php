@props([
    'id' => 'modalCrearUsuario',
    'titulo' => 'Registrar Nuevo Usuario',
    'accion' => route('agent.users.store'),
    'modoAjax' => false,
])

<x-agent.modal-usuario 
    :id="$id"
    :titulo="$titulo"
    :accion="$accion"
    :modoAjax="$modoAjax"
/>
