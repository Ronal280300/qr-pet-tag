<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequirePhoneNumber
{
    /**
     * Handle an incoming request.
     *
     * Si el usuario está autenticado pero no tiene número de teléfono,
     * redirigir al onboarding.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && !$user->phone) {
            // Permitir acceso a la ruta de onboarding y logout
            if (!$request->routeIs('onboarding.*') && !$request->routeIs('logout')) {
                return redirect()->route('onboarding.show')
                    ->with('info', 'Por favor, completa tu perfil agregando tu número de teléfono.');
            }
        }

        return $next($request);
    }
}
