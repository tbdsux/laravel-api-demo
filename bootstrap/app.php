<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle not found exceptions
        $exceptions->render(function (RouteNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Not Found!'], 404);
        });

        // Handle unauthorized exceptions
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json(['success' => false, 'message' => 'Unauthorized!'], 401);
        });

        // Handle all other exceptions
        $exceptions->render(function (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        });
    })->create();
