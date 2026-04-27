<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Define rate limiters after routes are loaded
            RateLimiter::for('login', function (Request $request) {
                return Limit::perMinute(5)->by($request->ip());
            });
            
            RateLimiter::for('assessment-submit', function (Request $request) {
                return Limit::perMinute(10)->by($request->ip());
            });
            
            RateLimiter::for('contact-submit', function (Request $request) {
                return Limit::perMinute(5)->by($request->ip());
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\IsAdmin::class,
        ]);
        
        // Exclude Paymob webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/paymob/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
