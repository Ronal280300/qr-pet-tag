<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Notifications\ResetPasswordEs;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ...
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            // Genera la URL absoluta segura a tu ruta de reset
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            // Retorna el MailMessage de nuestra notificación custom
            return (new ResetPasswordEs($url))->toMail($notifiable);
        });
    }
}
