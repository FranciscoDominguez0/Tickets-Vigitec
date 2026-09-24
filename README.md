# Sistema de Tickets - Arquitectura Laravel (Clean Code)

Esta es la estructura profesional implementada para migrar el "Sistema de Tickets" procedural a Laravel 11. 
Se ha disenado pensando en escalabilidad, multi-tenancy nativo y separacion de responsabilidades.

## 🗂️ Estructura de Directorios (app/)

\\\
app/
├── DataTransferObjects/ # (DTOs) Objetos para validar y tipar datos entre capas (ej. al crear un Ticket).
├── Enums/               # Constantes mapeadas (ej. TicketStatusEnum).
├── Helpers/             # Funciones globales utiles, reemplazando a includes/helpers.php.
├── Http/
│   ├── Controllers/
│   │   ├── Agent/       # Panel SCP (Agentes)
│   │   ├── Client/      # Portal Web (Clientes)
│   │   └── SuperAdmin/  # Panel de Administracion Global
│   ├── Middleware/      # Reglas de acceso, roles y verificacion de pagos (billing).
│   └── Requests/        # Validaciones de formularios (Form Requests).
├── Models/              # Modelos Eloquent mapeados 1 a 1 con la base de datos actual.
├── Repositories/        # Capa de acceso a datos. Extrae la logica SQL compleja fuera del controlador.
├── Services/            # Toda la Lógica de Negocio (ej. TicketService para asignaciones, notificaciones).
└── Traits/              # Funciones reutilizables. Destaca 'Tenantable' para el Multi-empresa.
\\\

## 🎭 Paneles y Vistas (resources/views/)

La aplicacion se divide en 3 portales claramente diferenciados:

1. **\gent/\**: Reemplaza a \upload/scp/\. Vistas exclusivas para que los agentes resuelvan tickets y tareas.
2. **\client/\**: Reemplaza a \upload/tickets.php\ y \cliente/\. El frontend donde el usuario crea y consulta tickets.
3. **\superadmin/\**: Reemplaza al panel de administracion superior. Gestiona empresas, billing, configs, etc.

## 🔑 Implementacion de Multi-Tenancy

Para no repetir \\ = empresaId();\ en todos lados, se ha creado \pp/Traits/Tenantable.php\.
Cualquier modelo (ej. \Ticket.php\) que use este Trait, filtrara automaticamente cualquier consulta por la empresa logueada, garantizando que los datos entre inquilinos no se filtren jamas (Global Scopes de Eloquent).

## 🚀 Flujo de un Request Ideal

1. **Ruta** (routes/web.php) -> 
2. **Middleware** (Verifica Auth y Rol) -> 
3. **Form Request** (Valida datos  y CSRF) -> 
4. **Controller** (Recibe todo validado) -> 
5. **DTO** (Empaqueta la informacion) -> 
6. **Service** (Aplica las reglas de negocio, envia emails) -> 
7. **Repository** (Guarda en la DB) -> 
8. **Controller** (Redirige con exito).
