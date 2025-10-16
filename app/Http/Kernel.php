<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Middleware globales (se ejecutan en todas las peticiones).
     */
    protected $middleware = [
        // Confiar en los hosts
        \App\Http\Middleware\TrustHosts::class,

        // Respeta proxies/reverse proxies (Cloudflare, Nginx, etc.)
        \App\Http\Middleware\TrustProxies::class,

        // CORS
        \Illuminate\Http\Middleware\HandleCors::class,

        // Modo mantenimiento
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Validación de tamaño de POST
        \Illuminate\Http\Middleware\ValidatePostSize::class,

        // Trimea strings y convierte vacíos a null
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Grupos de middleware.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // opcional
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Aliases de middleware para las rutas (Laravel 11/12).
     * Reemplaza al antiguo $routeMiddleware.
     */
    protected $middlewareAliases = [
        // Auth & permisos
        'auth'             => \App\Http\Middleware\Authenticate::class,
        'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can'              => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Firma de URLs y cache headers
        'signed'        => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,

        // Throttle y bindings
        'throttle'           => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'substituteBindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,

        // 🔒 Solo administradores (tu middleware)
        'admin' => \App\Http\Middleware\AdminOnly::class,

        // 🐾 Nuevo: bloqueo de gestión de mascotas para cuentas inactivas
        'can.manage.pets' => \App\Http\Middleware\EnsureClientCanManagePets::class,
    ];
}
