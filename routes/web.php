<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Autenticación (Cliente)
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard de ejemplo (protegido)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Bienvenido, ' . auth()->user()->email;
    })->name('dashboard');
});

// Rutas de Autenticación (Agentes/Staff)
Route::prefix('agent')->group(function () {
    Route::get('login', [\App\Http\Controllers\Agent\Auth\LoginController::class, 'showLoginForm'])->name('agent.login');
    Route::post('login', [\App\Http\Controllers\Agent\Auth\LoginController::class, 'login']);
    Route::post('logout', [\App\Http\Controllers\Agent\Auth\LoginController::class, 'logout'])->name('agent.logout');

    Route::middleware('auth:staff')->group(function () {
        Route::get('/dashboard', function () {
            return 'Bienvenido Agente, ' . auth('staff')->user()->firstname;
        })->name('agent.dashboard');
    });
});
