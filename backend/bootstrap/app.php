<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(api: __DIR__.'/../routes/api.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware): void {
        // Sanctum protects private API routes through auth:sanctum.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Laravel converts validation and authorization errors to JSON for API requests.
    })->create();
