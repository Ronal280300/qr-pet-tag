<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Dónde redirigir después de login/registro.
     * Ajusta a donde quieras, por ejemplo al dashboard del portal.
     */
    public const HOME = '/portal/dashboard';

    /**
     * Define tus rutas aquí si las usas,
     * no es estrictamente necesario tocar esto ahora.
     */
    public function boot(): void
    {
        parent::boot();

        // Ejemplo típico (si usas route files):
        // Route::middleware('web')->group(base_path('routes/web.php'));
        // Route::middleware('api')->prefix('api')->group(base_path('routes/api.php'));
    }
}
