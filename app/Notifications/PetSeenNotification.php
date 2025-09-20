<?php
// app/Notifications/PetSeenNotification.php
namespace App\Notifications;

use App\Models\PetPing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PetSeenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public PetPing $ping) {}

    public function via($notifiable): array
    {
        return ['mail','database']; // 'database' si usas un panel de notifs
    }

    public function toMail($notifiable): MailMessage
    {
        $p   = $this->ping->pet;
        $loc = collect([$this->ping->city, $this->ping->region, $this->ping->country])
                ->filter()->implode(', ');
        $map = ($this->ping->lat && $this->ping->lng)
             ? "https://www.google.com/maps?q={$this->ping->lat},{$this->ping->lng}"
             : route('portal.pets.show', $p);

        $msg = (new MailMessage)
            ->subject("Alguien escaneó el QR de {$p->name}")
            ->greeting("Hola {$notifiable->name} 👋")
            ->line("Hemos recibido una lectura del QR de **{$p->name}**.")
            ->line($loc ? "Ubicación aproximada: **{$loc}**." : "Se recibió un ping de ubicación.")
            ->line("Fuente: **{$this->ping->source}**" . ($this->ping->accuracy ? " (±{$this->ping->accuracy} m)" : ""))
            ->action('Ver en el mapa', $map)
            ->line('Sugerencia: actualiza el teléfono en el perfil público para que puedan contactarte.');
        return $msg;
    }

    public function toArray($notifiable): array
    {
        return [
            'pet_id'  => $this->ping->pet_id,
            'lat'     => $this->ping->lat,
            'lng'     => $this->ping->lng,
            'city'    => $this->ping->city,
            'region'  => $this->ping->region,
            'country' => $this->ping->country,
        ];
    }
}
