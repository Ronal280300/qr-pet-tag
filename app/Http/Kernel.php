<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     *
     * Estos se ejecutan en *todas* las peticiones.
     */
    protected $middleware = [
        // Confiar en los hosts (opcional; mantenlo si lo usas)
        \App\Http\Middleware\TrustHosts::class,

        // Respeta proxies/reverse proxies (Cloudflare, Nginx, etc.)
        \App\Http\Middleware\TrustProxies::class,

        // CORS (si no usas el de API Platform, este está bien)
        \Illuminate\Http\Middleware\HandleCors::class,

        // Validación de tamaño de POST
        \Illuminate\Http\Middleware\ValidatePostSize::class,

        // Trimea strings y convierte vacíos a null
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        // Modo mantenimiento
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
    ];

    /**
     * Middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            // Cookies y sesión
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,

            // Debe ir después de StartSession
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            // Protección CSRF
            \App\Http\Middleware\VerifyCsrfToken::class,

            // Enlaces modelo/route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Rate limiting
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',

            // Enlaces modelo/route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Route middleware (aliases).
     *
     * Se pueden aplicar individualmente en rutas.
     */
    protected $routeMiddleware = [
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
        'throttle'            => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'substituteBindings'  => \Illuminate\Routing\Middleware\SubstituteBindings::class,

        // 🔒 Solo administradores (tu middleware)
        'admin' => \App\Http\Middleware\AdminOnly::class,
    ];
}
