<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureClientCanManagePets
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Si no hay user o su cuenta está INACTIVA -> bloquear
        if (!$user || ($user->status ?? 'active') === 'inactive') {
            return redirect()
                ->route('portal.dashboard')
                ->with('error', 'Tu cuenta está inactiva. No puedes gestionar mascotas.');
        }

        return $next($request);
    }
}
