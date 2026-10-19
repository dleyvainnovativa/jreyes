<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // El panel /admin usa el middleware 'auth' nativo de Laravel.
        // (Se retiró el stub de Firebase 'firebase.auth'.)
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
