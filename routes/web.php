<?php

use App\Http\Controllers\Agent\DirectoryController;
use App\Http\Controllers\Agent\MapController;
use App\Http\Controllers\Agent\TicketController;
use App\Http\Controllers\Agent\TicketReportController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
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

// Dashboard de ejemplo (protegido)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $email = auth()->user()->email;
        $logoutUrl = route('logout');
        $csrf = csrf_field();

        return <<<HTML
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Dashboard Cliente</title>
            <style>
                body { font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; background-color: #f8fafc; }
                .card { background: white; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); text-align: center; }
                .btn { background: #ef4444; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 500; margin-top: 1.5rem; transition: background 0.2s; }
                .btn:hover { background: #dc2626; }
            </style>
        </head>
        <body>
            <div class="card">
                <h2 style="margin-top: 0; color: #1e293b;">¡Bienvenido!</h2>
                <p style="color: #64748b;">$email</p>
                <form action="$logoutUrl" method="POST">
                    $csrf
                    <button type="submit" class="btn">Cerrar Sesión</button>
                </form>
            </div>
        </body>
        </html>
        HTML;
    })->name('dashboard');
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
        Route::get('/directory', [DirectoryController::class, 'index'])->name('agent.directory');
        Route::get('/map', [MapController::class, 'index'])->name('agent.map');
        Route::get('/map/ubicaciones', [MapController::class, 'ubicaciones'])->name('agent.map.locations');
        Route::post('/map/update-location', [MapController::class, 'updateLocation'])->name('agent.location.update');

        // Tickets
        Route::get('/tickets', [TicketController::class, 'index'])->name('agent.tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('agent.tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('agent.tickets.store');
        Route::get('/ticket/{id}', [TicketController::class, 'show'])->name('agent.tickets.show');
        Route::post('/ticket/{id}/reply', [TicketController::class, 'responder'])->name('agent.tickets.reply');

        // Ticket Action Routes
        Route::post('/ticket/{id}/assign', [TicketController::class, 'asignar'])->name('agent.tickets.assign');
        Route::post('/ticket/{id}/transfer', [TicketController::class, 'transferir'])->name('agent.tickets.transfer');
        Route::post('/ticket/{id}/status', [TicketController::class, 'estado'])->name('agent.tickets.status');
        Route::delete('/ticket/{id}', [TicketController::class, 'destroy'])->name('agent.tickets.destroy');

        // Hilo del ticket
        Route::put('/thread/{id}', [TicketController::class, 'actualizarHilo'])->name('agent.tickets.thread.update');
        Route::delete('/thread/{id}', [TicketController::class, 'eliminarHilo'])->name('agent.tickets.thread.destroy');

        Route::get('/tickets/billing', [TicketReportController::class, 'billing'])->name('agent.tickets.billing');
        Route::get('/ticket/{id}/report-sheet', [TicketReportController::class, 'reportSheet'])->name('agent.tickets.report_sheet');
        Route::post('/ticket/{id}/report-sheet', [TicketReportController::class, 'storeReport'])->name('agent.tickets.report.store');

        // Reporte e Inventario
        Route::get('/reports', function () {
            return view('agent.reports.index');
        })->name('agent.reports.index');
        Route::get('/inventory', function () {
            return view('agent.inventory.index');
        })->name('agent.inventory.index');

        // Usuarios
        Route::get('/users/directory', function () {
            return view('agent.users.directory');
        })->name('agent.users.directory');
        Route::get('/users/orgs', function () {
            return view('agent.users.orgs');
        })->name('agent.users.orgs');

        // Perfil
        Route::get('/profile', function () {
            return view('agent.profile.index');
        })->name('agent.profile.index');
    });
});
