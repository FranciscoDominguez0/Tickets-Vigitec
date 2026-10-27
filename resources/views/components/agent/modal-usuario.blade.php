@props([
    'id' => 'modalCrearUsuario',
    'titulo' => 'Registrar Nuevo Usuario',
    'accion' => route('agent.users.store'),
    'modoAjax' => false,
])

<x-modal 
    :id="$id"
    :titulo="$titulo"
    subtitulo="Ingresa los datos personales del usuario/cliente"
    icono="bi-person-plus-fill"
    colorIcono="text-danger"
    :accion="$accion"
    metodo="POST"
    textoBoton="Guardar Usuario"
    iconoBoton="bi-save"
    colorBoton="btn-danger"
    tamano="modal-lg"
    :modoAjax="$modoAjax"
>
    <div class="row g-3">
        <div class="col-12 col-md-6">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="firstname" class="form-control" placeholder="Ej. Juan" required>
        </div>

        <div class="col-12 col-md-6">
            <label class="form-label">Apellido <span class="text-danger">*</span></label>
            <input type="text" name="lastname" class="form-control" placeholder="Ej. Pérez" required>
        </div>

        <div class="col-12 col-md-6">
            <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" placeholder="usuario@ejemplo.com" required>
        </div>

        <div class="col-12 col-md-6">
            <label class="form-label">Teléfono / Celular</label>
            <input type="text" name="phone" class="form-control" placeholder="+507 6000-0000">
        </div>

        <div class="col-12 col-md-6">
            <label class="form-label">Contraseña de Acceso</label>
            <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres (Opcional)">
            <div class="form-hint">Si se deja vacía, se asignará por defecto <code>password123</code>.</div>
        </div>

        <div class="col-12 col-md-6">
            <label class="form-label">Dirección Física</label>
            <input type="text" name="address" class="form-control" placeholder="Ciudad, edificio, oficina...">
        </div>
    </div>
</x-modal>
