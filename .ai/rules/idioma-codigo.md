---
description: Regla obligatoria de idioma en el código fuente (Español para todo código propio)
globs:
  - "app/**/*"
  - "routes/**/*"
  - "tests/**/*"
  - "database/**/*"
---

# Idioma Obligatorio del Código

## Regla Estricta
Todo el código propio desarrollado para este proyecto (métodos, nombres de funciones, variables, controladores, servicios, repositorios, DTOs, tests y comentarios) **DEBE estar en español**.

### Ejemplos Correctos:
- Controladores: `listar()`, `crear()`, `guardar()`, `mostrar()`, `editar()`, `actualizar()`, `eliminar()`, `cambiarEstado()`
- Servicios: `generarNumeroTicket()`, `crearHiloInicial()`, `agregarRespuesta()`, `actualizarEstado()`, `guardarFirmaDesdeBase64()`
- Repositorios: `obtenerFiltrados()`, `buscarPorId()`, `crear()`, `actualizar()`, `eliminar()`, `obtenerEstadisticas()`
- DTOs: `desdeSolicitud()`, `aArreglo()`
- Variables: `$departamentos`, `$usuario`, `$datosTicket`, `$estadisticas`
- Tests: `test_agente_puede_crear_un_ticket()`, `test_falla_validacion_al_registrar_usuario()`

## Única Excepción
Solo se permite el inglés para las funciones, palabras reservadas y métodos propios y nativos del framework **Laravel** o de **PHP** (ejemplos: `validate()`, `create()`, `find()`, `where()`, `get()`, `first()`, `redirect()`, `with()`, `assertStatus()`, `assertSee()`, etc.).
