---
description: Reglas y documentaciÃ³n de la arquitectura de Login (Cliente y Agente)
globs:
  - "app/Http/Controllers/Auth/**/*"
  - "resources/views/auth/**/*"
  - "resources/views/components/auth/**/*"
---

# Arquitectura de Login

Este proyecto tiene dos tipos de login planificados:
1. **Login de Cliente** (Usuarios regulares en la tabla `users`)
2. **Login de Agente** (Personal/Staff, presumiblemente en `staff` o similar)

## Componentes Reutilizables de UI
Dado que la UI de ambos formularios de acceso compartirÃ¡ un "mismo diseÃ±o", se deben utilizar los siguientes componentes de Blade ubicados en `resources/views/components/auth/`:

- `<x-auth.layout>`: Layout base que incluye los estilos principales (`login.css` y `client_dark.css`).
- `<x-auth.input>`: Componente reutilizable para los inputs de texto/password.
- `<x-auth.button>`: BotÃ³n de envÃ­o con estado de carga.
- `<x-auth.alert>`: Alertas flash (errores, Ã©xitos, advertencias).

## Buenas PrÃ¡cticas y Clean Code
- **Controladores Limpios**: LÃ³gica de negocio fuera del controlador si es compleja; el AuthController solo debe manejar validaciÃ³n y `Auth::attempt`.
- **FormRequests**: Usa Form Requests para la validaciÃ³n (ej. `LoginRequest`) si el formulario crece, o validaciÃ³n nativa `$request->validate()` para un login sencillo.
- **CSRF**: AsegÃºrate de incluir `@csrf` en todos los formularios.
- **Traducciones**: Usa comentarios y variables en espaÃ±ol/inglÃ©s claro. La instrucciÃ³n pide "comentarios en espaÃ±ol sencillo".
- **Rate Limiting**: Laravel Breeze/Jetstream trae `RateLimiter` por defecto. Si se implementa manualmente, usar `RateLimiter` en el login.
