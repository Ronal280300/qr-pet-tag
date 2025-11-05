<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;


class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        // Puedes quitar ->stateless() en local si quieres mantener el state
        return Socialite::driver('google')
            ->scopes(['openid','profile','email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            // Usa stateless si estás detrás de proxy/CDN o hay problemas de state.
            $google = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('login')
                ->with('error', 'No se pudo iniciar sesión con Google. Intenta nuevamente.');
        }

        // Buscamos por email (preferible para unir cuentas)
        $user = User::where('email', $google->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'              => $google->getName() ?? $google->getNickname() ?? 'Usuario Google',
                'email'             => $google->getEmail(),
                'email_verified_at' => now(),
                'google_id'         => $google->getId(),
                // 'avatar_url'      => $google->getAvatar(), // si tienes esta columna
                'password'          => bcrypt(Str::random(40)), // placeholder
            ]);
        } else {
            // Vincula si aún no está vinculado
            if (!$user->google_id) {
                $user->google_id = $google->getId();
            }
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
            }
            // if (!$user->avatar_url && $google->getAvatar()) $user->avatar_url = $google->getAvatar();
            $user->save();
        }

        Auth::login($user, remember: true);

        // Si el usuario no tiene teléfono, redirigir al onboarding
        if (!$user->phone) {
            return redirect()->route('onboarding.show')
                ->with('info', 'Por favor, completa tu perfil para continuar.');
        }

        // Llévalo a planes
        return redirect()->intended(route('plans.index'));
    }
}
