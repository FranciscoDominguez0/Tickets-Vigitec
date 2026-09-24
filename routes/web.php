<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Autenticación (Cliente)
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);

    Route::get('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
});
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard de ejemplo (protegido)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Bienvenido, ' . auth()->user()->email;
    })->name('dashboard');
});

// Rutas de Autenticación (Agentes/Staff)
Route::prefix('agent')->group(function () {
    Route::middleware('guest:staff')->group(function () {
        Route::get('login', [\App\Http\Controllers\Agent\Auth\LoginController::class, 'showLoginForm'])->name('agent.login');
        Route::post('login', [\App\Http\Controllers\Agent\Auth\LoginController::class, 'login']);
    });
    Route::post('logout', [\App\Http\Controllers\Agent\Auth\LoginController::class, 'logout'])->name('agent.logout');

    Route::middleware('auth:staff')->group(function () {
        Route::get('/dashboard', function () {
            return view('agent.dashboard');
        })->name('agent.dashboard');
        
        // Panel de control
        Route::get('/directory', function () { return view('agent.directory.index'); })->name('agent.directory');
        Route::get('/map', function () { return view('agent.map.index'); })->name('agent.map');

        // Tickets
        Route::get('/tickets', function () { return view('agent.tickets.index'); })->name('agent.tickets.index');
        Route::get('/tickets/billing', function () { return view('agent.tickets.billing'); })->name('agent.tickets.billing');
        Route::get('/tickets/report-sheet', function () { return view('agent.tickets.report_sheet'); })->name('agent.tickets.report_sheet');

        // Reporte e Inventario
        Route::get('/reports', function () { return view('agent.reports.index'); })->name('agent.reports.index');
        Route::get('/inventory', function () { return view('agent.inventory.index'); })->name('agent.inventory.index');

        // Usuarios
        Route::get('/users/directory', function () { return view('agent.users.directory'); })->name('agent.users.directory');
        Route::get('/users/orgs', function () { return view('agent.users.orgs'); })->name('agent.users.orgs');

        // Perfil
        Route::get('/profile', function () { return view('agent.profile.index'); })->name('agent.profile.index');
    });
});
