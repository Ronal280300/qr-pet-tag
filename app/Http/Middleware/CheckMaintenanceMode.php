<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar si el modo mantenimiento está activo
        $maintenanceMode = Setting::get('maintenance_mode', false);

        // Si el modo mantenimiento está activo
        if ($maintenanceMode) {
            // Permitir acceso a administradores
            if (Auth::check() && Auth::user()->is_admin) {
                return $next($request);
            }

            // Bloquear acceso a usuarios regulares y no autenticados
            return redirect('/')->with('warning', 'El sistema se encuentra en mantenimiento. Por favor, intente más tarde.');
        }

        return $next($request);
    }
}
