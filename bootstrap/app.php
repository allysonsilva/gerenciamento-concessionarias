<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->throttleWithRedis()
                   ->append(\App\Http\Middleware\SentryContext::class)
                   ->group('api', [
                        'throttle:safe',
                        \Illuminate\Routing\Middleware\SubstituteBindings::class,
                   ])
                   ->alias([
                       'auth' => \App\Http\Middleware\JwtAuthenticate::class,
                   ])
                   ->group('auth-verified', [
                       'auth',
                       'verified'
                   ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
