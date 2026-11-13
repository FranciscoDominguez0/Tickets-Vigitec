<?php

use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\CorreoController;
use App\Http\Controllers\Admin\DepartamentoController;
use App\Http\Controllers\Admin\NotificacionController;
use App\Http\Controllers\Admin\PanelControlController;
use App\Http\Controllers\Admin\PersonalController;
use App\Http\Controllers\Admin\RolController;
use App\Http\Controllers\Admin\SecuenciaController;
use App\Http\Controllers\Admin\TemaAyudaController;
use App\Http\Controllers\Admin\TicketBorradoController;
use App\Http\Controllers\Agent\DirectoryController;
use App\Http\Controllers\Agent\MapController;
use App\Http\Controllers\Agent\OrganizationController;
use App\Http\Controllers\Agent\ProfileController;
use App\Http\Controllers\Agent\TicketController;
use App\Http\Controllers\Agent\TicketReportController;
use App\Http\Controllers\Agent\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Client\TicketController as ClientTicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Autenticación (Cliente)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'mostrarFormularioLogin'])->name('login');
    Route::post('login', [LoginController::class, 'iniciarSesion']);

    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});
Route::post('logout', [LoginController::class, 'cerrarSesion'])->name('logout');

// Rutas del Portal de Clientes (protegidas)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ClientTicketController::class, 'listar'])->name('dashboard');
    Route::get('/tickets', [ClientTicketController::class, 'listar'])->name('client.tickets.index');
    Route::get('/tickets/create', [ClientTicketController::class, 'crear'])->name('client.tickets.create');
    Route::post('/tickets', [ClientTicketController::class, 'guardar'])->name('client.tickets.store');
    Route::get('/tickets/{id}', [ClientTicketController::class, 'mostrar'])->name('client.tickets.show');
    Route::post('/tickets/{id}/reply', [ClientTicketController::class, 'responder'])->name('client.tickets.reply');
});

// Rutas de Autenticación (Agentes/Staff)
Route::prefix('agent')->group(function () {
    Route::middleware('guest:staff')->group(function () {
        Route::get('login', [App\Http\Controllers\Agent\Auth\LoginController::class, 'mostrarFormularioLogin'])->name('agent.login');
        Route::post('login', [App\Http\Controllers\Agent\Auth\LoginController::class, 'iniciarSesion']);
    });
    Route::post('logout', [App\Http\Controllers\Agent\Auth\LoginController::class, 'cerrarSesion'])->name('agent.logout');

    Route::middleware('auth:staff')->group(function () {
        Route::get('/dashboard', function () {
            return view('agent.dashboard');
        })->name('agent.dashboard');

        // Panel de control
        Route::get('/directory', [DirectoryController::class, 'listar'])->name('agent.directory');
        Route::get('/map', [MapController::class, 'index'])->name('agent.map');
        Route::get('/map/ubicaciones', [MapController::class, 'ubicaciones'])->name('agent.map.locations');
        Route::post('/map/update-location', [MapController::class, 'actualizarUbicacion'])->name('agent.location.update');

        // Tickets
        Route::get('/tickets', [TicketController::class, 'listar'])->name('agent.tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'crear'])->name('agent.tickets.create');
        Route::post('/tickets', [TicketController::class, 'guardar'])->name('agent.tickets.store');
        Route::get('/ticket/{id}', [TicketController::class, 'mostrar'])->name('agent.tickets.show');
        Route::post('/ticket/{id}/reply', [TicketController::class, 'responder'])->name('agent.tickets.reply');

        // Ticket Action Routes
        Route::post('/ticket/{id}/assign', [TicketController::class, 'asignar'])->name('agent.tickets.assign');
        Route::post('/ticket/{id}/transfer', [TicketController::class, 'transferir'])->name('agent.tickets.transfer');
        Route::post('/ticket/{id}/status', [TicketController::class, 'estado'])->name('agent.tickets.status');
        Route::post('/ticket/{id}/request-signature', [TicketController::class, 'guardarFirma'])->name('agent.tickets.request_signature');
        Route::get('/ticket/{id}/pdf', [TicketController::class, 'descargarPdf'])->name('agent.tickets.pdf');
        Route::delete('/ticket/{id}', [TicketController::class, 'eliminar'])->name('agent.tickets.destroy');

        // Hilo del ticket
        Route::put('/thread/{id}', [TicketController::class, 'actualizarHilo'])->name('agent.tickets.thread.update');
        Route::delete('/thread/{id}', [TicketController::class, 'eliminarHilo'])->name('agent.tickets.thread.destroy');

        Route::get('/tickets/reportes', [TicketReportController::class, 'facturacion'])->name('agent.tickets.reports');
        Route::get('/ticket/{id}/report-sheet', [TicketReportController::class, 'hojaReporte'])->name('agent.tickets.report_sheet');
        Route::post('/ticket/{id}/report-sheet', [TicketReportController::class, 'guardarReporte'])->name('agent.tickets.report.store');

        // Reporte e Inventario
        Route::get('/reports', function () {
            return view('agent.reports.index');
        })->name('agent.reports.index');
        Route::get('/inventory', function () {
            return view('agent.inventory.index');
        })->name('agent.inventory.index');

        // Organizaciones
        Route::get('/users/orgs', [OrganizationController::class, 'listar'])->name('agent.users.orgs');
        Route::get('/organizations', [OrganizationController::class, 'listar'])->name('agent.organizations.index');
        Route::post('/organizations', [OrganizationController::class, 'guardar'])->name('agent.organizations.store');
        Route::get('/organizations/{id}', [OrganizationController::class, 'mostrar'])->whereNumber('id')->name('agent.organizations.show');
        Route::put('/organizations/{id}', [OrganizationController::class, 'actualizar'])->whereNumber('id')->name('agent.organizations.update');
        Route::delete('/organizations/{id}', [OrganizationController::class, 'eliminar'])->whereNumber('id')->name('agent.organizations.destroy');
        Route::post('/organizations/{id}/members', [OrganizationController::class, 'vincularUsuario'])->whereNumber('id')->name('agent.organizations.members.store');
        Route::delete('/organizations/{id}/members/{userId}', [OrganizationController::class, 'desvincularUsuario'])->whereNumber('id')->whereNumber('userId')->name('agent.organizations.members.destroy');

        // Usuarios
        Route::get('/users', fn () => redirect()->route('agent.users.directory'))->name('agent.users.index');
        Route::get('/users/directory', [UserController::class, 'listar'])->name('agent.users.directory');
        Route::get('/users/create', [UserController::class, 'crear'])->name('agent.users.create');
        Route::post('/users', [UserController::class, 'guardar'])->name('agent.users.store');
        Route::get('/users/{id}', [UserController::class, 'mostrar'])->whereNumber('id')->name('agent.users.show');
        Route::get('/users/{id}/edit', [UserController::class, 'editar'])->whereNumber('id')->name('agent.users.edit');
        Route::put('/users/{id}', [UserController::class, 'actualizar'])->whereNumber('id')->name('agent.users.update');
        Route::post('/users/{id}/toggle-status', [UserController::class, 'cambiarEstado'])->whereNumber('id')->name('agent.users.toggle_status');
        Route::delete('/users/{id}', [UserController::class, 'eliminar'])->whereNumber('id')->name('agent.users.destroy');

        // Perfil
        Route::get('/profile', [ProfileController::class, 'mostrar'])->name('agent.profile.index');
        Route::put('/profile', [ProfileController::class, 'actualizar'])->name('agent.profile.update');
        Route::put('/profile/password', [ProfileController::class, 'cambiarContrasena'])->name('agent.profile.password');

        // Redirecciones de compatibilidad hacia el Panel Administrador (/admin/...)
        Route::get('/settings', function (Request $solicitud) {
            $pestaña = $solicitud->query('t', 'pages');

            return match ($pestaña) {
                'billing', 'pagoempresa' => redirect()->route('admin.settings.billing'),
                'tickets', 'priority' => redirect()->route('admin.settings.tickets'),
                'tasks' => redirect()->route('admin.settings.tasks'),
                'agents' => redirect()->route('admin.settings.agents'),
                'users' => redirect()->route('admin.settings.users'),
                'emails', 'email' => redirect()->route('admin.email'),
                'banlist' => redirect()->route('admin.banlist'),
                default => redirect()->route('admin.settings.pages'),
            };
        })->name('agent.settings.pages');

        Route::get('/pagoempresa', fn () => redirect()->route('admin.settings.billing'));
        Route::get('/billing', fn () => redirect()->route('admin.settings.billing'));
        Route::get('/empresa', fn () => redirect()->route('admin.settings.pages'));
        Route::get('/priority', fn () => redirect()->route('admin.settings.tickets'));
        Route::get('/email', fn () => redirect()->route('admin.email'));
        Route::get('/emails', fn () => redirect()->route('admin.email'));
        Route::get('/emaillog', fn () => redirect()->route('admin.email'));
        Route::get('/banlist', fn () => redirect()->route('admin.banlist'));
        Route::get('/emailtest', fn () => redirect()->route('admin.emailtest'));
        Route::get('/logs', fn () => redirect()->route('admin.logs'));
        Route::get('/audit', fn () => redirect()->route('admin.audit'));
        Route::get('/staff', fn () => redirect()->route('admin.staff'));
        Route::get('/roles', fn () => redirect()->route('admin.roles'));
        Route::get('/departments', fn () => redirect()->route('admin.departments'));
        Route::get('/helptopics', fn () => redirect()->route('admin.helptopics'));
        Route::get('/deleted-tickets', fn () => redirect()->route('admin.deleted_tickets'));
    });
});

// Rutas del Panel Administrador (/admin/...)
Route::prefix('admin')->name('admin.')->middleware(['auth:staff', 'admin'])->group(function () {
    // 1. Configuración
    Route::get('/settings', [ConfiguracionController::class, 'perfilEmpresa'])->name('settings.pages');
    Route::post('/settings/pages', [ConfiguracionController::class, 'guardarPerfilEmpresa'])->name('settings.pages.store');
    Route::get('/settings/billing', [ConfiguracionController::class, 'facturacion'])->name('settings.billing');
    Route::get('/settings/tickets', [ConfiguracionController::class, 'solicitudes'])->name('settings.tickets');
    Route::post('/settings/tickets', [ConfiguracionController::class, 'guardarSolicitudes'])->name('settings.tickets.store');
    Route::get('/settings/tickets/sequences', [SecuenciaController::class, 'listar'])->name('settings.sequences');
    Route::get('/sequences', [SecuenciaController::class, 'listar'])->name('sequences');
    Route::post('/sequences', [SecuenciaController::class, 'guardar'])->name('sequences.store');
    Route::put('/sequences/{id}', [SecuenciaController::class, 'actualizar'])->whereNumber('id')->name('sequences.update');
    Route::delete('/sequences/{id}', [SecuenciaController::class, 'eliminar'])->whereNumber('id')->name('sequences.destroy');
    Route::get('/settings/tasks', [ConfiguracionController::class, 'tareas'])->name('settings.tasks');
    Route::get('/settings/agents', [ConfiguracionController::class, 'agentes'])->name('settings.agents');
    Route::get('/settings/users', [ConfiguracionController::class, 'usuarios'])->name('settings.users');

    // 2. Panel de Control (Logs y Auditoría)
    Route::get('/logs', [PanelControlController::class, 'registros'])->name('logs');
    Route::get('/audit', [PanelControlController::class, 'auditoria'])->name('audit');

    // 3. Notificaciones
    Route::get('/notifications', [NotificacionController::class, 'listar'])->name('notifications');
    Route::post('/notifications/send', [NotificacionController::class, 'enviar'])->name('notifications.send');
    Route::post('/notifications/{id}/read', [NotificacionController::class, 'marcarLeida'])->whereNumber('id')->name('notifications.read');
    Route::post('/notifications/read-all', [NotificacionController::class, 'marcarTodasLeidas'])->name('notifications.read_all');
    Route::delete('/notifications/{id}', [NotificacionController::class, 'eliminar'])->whereNumber('id')->name('notifications.destroy');
    Route::post('/notifications/mass-process', [NotificacionController::class, 'procesarMasivo'])->name('notifications.mass');
    Route::post('/notifications/recipients', [NotificacionController::class, 'guardarDestinatarios'])->name('notifications.recipients');

    // 4. Temas de Ayuda
    Route::get('/helptopics', [TemaAyudaController::class, 'listar'])->name('helptopics');
    Route::post('/helptopics', [TemaAyudaController::class, 'guardar'])->name('helptopics.store');
    Route::put('/helptopics/{id}', [TemaAyudaController::class, 'actualizar'])->whereNumber('id')->name('helptopics.update');
    Route::post('/helptopics/{id}/toggle', [TemaAyudaController::class, 'alternarEstado'])->whereNumber('id')->name('helptopics.toggle');
    Route::delete('/helptopics/{id}', [TemaAyudaController::class, 'eliminar'])->whereNumber('id')->name('helptopics.destroy');
    Route::post('/helptopics/mass-process', [TemaAyudaController::class, 'procesarMasivo'])->name('helptopics.mass');

    // 5. Tickets Borrados
    Route::get('/deleted-tickets', [TicketBorradoController::class, 'listar'])->name('deleted_tickets');
    Route::post('/deleted-tickets/{id}/restore', [TicketBorradoController::class, 'restaurar'])->whereNumber('id')->name('deleted_tickets.restore');
    Route::delete('/deleted-tickets/{id}/purge', [TicketBorradoController::class, 'purgar'])->whereNumber('id')->name('deleted_tickets.purge');

    // 6. Correos Electrónicos
    Route::get('/email', [CorreoController::class, 'listar'])->name('email');
    Route::post('/email', [CorreoController::class, 'guardar'])->name('email.store');
    Route::put('/email/{id}', [CorreoController::class, 'actualizar'])->whereNumber('id')->name('email.update');
    Route::post('/email/{id}/default', [CorreoController::class, 'marcarPredeterminada'])->whereNumber('id')->name('email.default');
    Route::delete('/email/{id}', [CorreoController::class, 'eliminar'])->whereNumber('id')->name('email.destroy');
    Route::get('/emails', fn () => redirect()->route('admin.email'));
    Route::get('/emaillog', fn () => redirect()->route('admin.email'));
    Route::get('/banlist', [CorreoController::class, 'listaProhibidos'])->name('banlist');
    Route::post('/banlist', [CorreoController::class, 'guardarProhibido'])->name('banlist.store');
    Route::post('/banlist/{id}/toggle', [CorreoController::class, 'alternarEstadoProhibido'])->whereNumber('id')->name('banlist.toggle');
    Route::delete('/banlist/{id}', [CorreoController::class, 'eliminarProhibido'])->whereNumber('id')->name('banlist.destroy');
    Route::get('/emailtest', [CorreoController::class, 'diagnostico'])->name('emailtest');
    Route::post('/emailtest', [CorreoController::class, 'enviarPrueba'])->name('emailtest.send');

    // 7. Agentes
    Route::get('/staff', [PersonalController::class, 'agentes'])->name('staff');
    Route::post('/staff', [PersonalController::class, 'guardar'])->name('staff.store');
    Route::put('/staff/{id}', [PersonalController::class, 'actualizar'])->whereNumber('id')->name('staff.update');
    Route::post('/staff/{id}/toggle', [PersonalController::class, 'alternarEstado'])->whereNumber('id')->name('staff.toggle');
    Route::post('/staff/{id}/reset-password', [PersonalController::class, 'restablecerClave'])->whereNumber('id')->name('staff.reset_password');
    Route::delete('/staff/{id}', [PersonalController::class, 'eliminar'])->whereNumber('id')->name('staff.destroy');
    Route::post('/staff/mass-process', [PersonalController::class, 'procesarMasivo'])->name('staff.mass');
    Route::get('/roles', [RolController::class, 'listar'])->name('roles');
    Route::post('/roles', [RolController::class, 'guardar'])->name('roles.store');
    Route::post('/roles/mass-process', [RolController::class, 'procesarMasivo'])->name('roles.mass');
    Route::get('/roles/{nombre}/permissions', [RolController::class, 'mostrarPermisos'])->name('roles.permissions');
    Route::post('/roles/{nombre}/permissions', [RolController::class, 'guardarPermisos'])->name('roles.permissions.store');
    Route::get('/departments', [DepartamentoController::class, 'listar'])->name('departments');
    Route::post('/departments', [DepartamentoController::class, 'guardar'])->name('departments.store');
    Route::put('/departments/{id}', [DepartamentoController::class, 'actualizar'])->whereNumber('id')->name('departments.update');
    Route::post('/departments/mass-process', [DepartamentoController::class, 'procesarMasivo'])->name('departments.mass');
});
