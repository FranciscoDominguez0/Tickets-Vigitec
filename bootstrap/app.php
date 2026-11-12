<?php

use App\Http\Middleware\VerificarAdministrador;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => VerificarAdministrador::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('agent*') || $request->is('admin*')) {
                return route('agent.login');
            }

            return route('login');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if (auth('staff')->check()) {
                return route('agent.dashboard');
            }

            return route('dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'El tamaño total de los archivos adjuntos supera el límite permitido por el servidor.',
                ], 413);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'El tamaño total de los archivos adjuntos supera el límite permitido por el servidor. Por favor, selecciona archivos de menor tamaño.');
        });
    })->create();
