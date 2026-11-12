<x-agent.admin-layout>
    @push('styles')
    <style>
        .test-hero {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .test-hero-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .test-card {
            background-color: #16191d;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .test-card .card-header {
            background-color: #121417;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.1rem 1.5rem;
            color: #ffffff;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .test-card .card-body {
            padding: 1.5rem;
        }

        .test-card .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }

        .test-card .form-control,
        .test-card .form-select {
            background-color: #121417;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.92rem;
        }

        .test-card .form-control:focus,
        .test-card .form-select:focus {
            background-color: #121417;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
        }
    </style>
    @endpush

    <!-- Hero Header -->
    <div class="test-hero">
        <div class="d-flex align-items-center gap-3">
            <div class="test-hero-icon">
                <i class="bi bi-activity"></i>
            </div>
            <div>
                <h1 class="h4 fw-bold text-white mb-1">Diagnóstico y Prueba de Correo</h1>
                <p class="text-secondary small mb-0">Verifica la conectividad SMTP y el envío real de correos electrónicos a través de las cuentas configuradas.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-4 d-flex align-items-center gap-2" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 mb-4 d-flex align-items-center gap-2" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Formulario de Prueba -->
        <div class="col-lg-8">
            <div class="test-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-send text-danger"></i>
                    <span>Realizar Prueba de Envío</span>
                </div>
                <div class="card-body">
                    @if($cuentas->isEmpty())
                        <div class="alert alert-warning border-0 text-white rounded-3 p-3 mb-0" style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3) !important;">
                            <i class="bi bi-exclamation-circle me-1 text-warning"></i>
                            No hay cuentas de correo configuradas en el sistema. Debes registrar al menos una cuenta en <a href="{{ route('admin.email') }}" class="text-white fw-bold text-decoration-underline">Correos Electrónicos</a> antes de realizar diagnósticos.
                        </div>
                    @else
                        <form action="{{ route('admin.emailtest.send') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="from_id" class="form-label">Cuenta Emisora (De) *</label>
                                <select name="from_id" id="from_id" class="form-select @error('from_id') is-invalid @enderror" required>
                                    @foreach($cuentas as $cuenta)
                                        <option value="{{ $cuenta->id }}" {{ old('from_id') == $cuenta->id ? 'selected' : ($cuenta->is_default ? 'selected' : '') }}>
                                            {{ $cuenta->name }} &lt;{{ $cuenta->email }}&gt; {{ $cuenta->is_default ? '(Predeterminada)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('from_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="to" class="form-label">Destinatario de Prueba (Para) *</label>
                                <input type="email" 
                                       name="to" 
                                       id="to" 
                                       class="form-control @error('to') is-invalid @enderror" 
                                       value="{{ old('to', auth('staff')->user()->email ?? '') }}" 
                                       placeholder="tu-correo@ejemplo.com" 
                                       required>
                                @error('to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label">Asunto del Mensaje *</label>
                                <input type="text" 
                                       name="subject" 
                                       id="subject" 
                                       class="form-control @error('subject') is-invalid @enderror" 
                                       value="{{ old('subject', 'Prueba de diagnóstico SMTP - ' . config('app.name', 'Vigitec')) }}" 
                                       required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="body" class="form-label">Contenido del Mensaje *</label>
                                <textarea name="body" 
                                          id="body" 
                                          class="form-control @error('body') is-invalid @enderror" 
                                          rows="5" 
                                          required>{{ old('body', "Este es un mensaje de prueba para verificar la correcta configuración y salida de correos a través del servidor SMTP.\n\nFecha y hora: " . now()->format('d/m/Y H:i:s')) }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: #ef4444; border-color: #ef4444;">
                                    <i class="bi bi-send-check me-1"></i> Enviar Correo de Prueba
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Panel Informativo Lateral -->
        <div class="col-lg-4">
            <div class="test-card">
                <div class="card-header">
                    <span>Instrucciones de Diagnóstico</span>
                </div>
                <div class="card-body">
                    <ul class="text-secondary small mb-0 d-flex flex-column gap-3 ps-3">
                        <li>
                            <strong class="text-white">Verificación SMTP:</strong> La prueba intentará conectarse al servidor de correo saliente configurado en la cuenta emisora seleccionada.
                        </li>
                        <li>
                            <strong class="text-white">Bandeja de Entrada:</strong> Revisa tu bandeja de entrada y la carpeta de spam para verificar la entrega del mensaje.
                        </li>
                        <li>
                            <strong class="text-white">Tiempo de Respuesta:</strong> Si el servidor tarda más de 30 segundos o falla, comprueba el puerto (25, 465, 587) y el cifrado (TLS/SSL).
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-agent.admin-layout>

