<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordEs extends Notification
{
    public string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $brand    = config('app.name', 'QR-Pet Tag');
        $passwords = config('auth.defaults.passwords');
        $minutes   = (int) (config("auth.passwords.$passwords.expire") ?? 60);

        return (new MailMessage)
            ->subject("Restablecer contraseña - {$brand}")
            // Usamos una vista HTML propia (no Markdown por defecto)
            ->view('emails.auth.reset-password', [
                'brand'   => $brand,
                'user'    => $notifiable,
                'url'     => $this->url,
                'minutes' => $minutes,
            ]);
    }
}
