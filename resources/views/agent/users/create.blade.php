<x-agent.layout>
    @push('styles')
    <style>
        .form-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 2rem;
            max-width: 800px;
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #fff;
            padding: 0.65rem 0.9rem;
            border-radius: 8px;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #fff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
        .required-star {
            color: #ef4444;
        }
        .form-hint {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.45);
            margin-top: 0.35rem;
        }
    </style>
    @endpush

    <div class="container-fluid px-0">
        <!-- Encabezado -->
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('agent.users.directory') }}" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 8px;">
                <i class="bi bi-arrow-left me-1"></i> Volver
            </a>
            <div>
                <h1 class="h3 text-white mb-0 fw-bold">Registrar Nuevo Usuario</h1>
                <p class="text-muted mb-0 small">Crea la cuenta de un cliente en el sistema</p>
            </div>
        </div>

        <!-- Formulario -->
        <div class="form-card mx-auto mx-md-0">
            <form action="{{ route('agent.users.store') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-4 border-0 rounded-3 shadow-sm" style="background: rgba(239, 68, 68, 0.15); color: #f87171;">
                        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrige los siguientes errores:</div>
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nombre <span class="required-star">*</span></label>
                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}" placeholder="Ej. Juan" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Apellido <span class="required-star">*</span></label>
                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}" placeholder="Ej. Pérez" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Correo Electrónico <span class="required-star">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="cliente@ejemplo.com" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+507 6000-0000">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Organización / Empresa</label>
                        <select name="organization_id" class="form-select @error('organization_id') is-invalid @enderror">
                            <option value="">-- Sin organización / Particular --</option>
                            @foreach ($organizaciones as $org)
                                <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                                    {{ $org->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Estado Inicial</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Activo (Permite acceso y creación de tickets)</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactivo (Bloqueado)</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Dirección Física</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="Calle, edificio, oficina...">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Contraseña de Acceso</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 6 caracteres (Opcional)">
                        <div class="form-hint">Si se deja en blanco, se asignará por defecto <code>password123</code>.</div>
                    </div>
                </div>

                <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('agent.users.directory') }}" class="btn btn-outline-secondary px-4" style="border-radius: 8px;">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-danger px-4 fw-bold" style="background-color: #ef4444; border-color: #ef4444; border-radius: 8px;">
                        <i class="bi bi-save me-1"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-agent.layout>
