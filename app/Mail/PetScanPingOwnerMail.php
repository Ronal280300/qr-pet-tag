<?php

namespace App\Mail;

use App\Models\Pet;
use App\Models\PetPing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PetPingOwnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public Pet $pet;
    public PetPing $ping;

    public function __construct(Pet $pet, PetPing $ping)
    {
        $this->pet  = $pet;
        $this->ping = $ping;
    }

    public function build()
    {
        $maps = null;
        if ($this->ping->lat && $this->ping->lng) {
            $maps = 'https://www.google.com/maps?q=' . $this->ping->lat . ',' . $this->ping->lng;
        }

        return $this->subject('Alguien escaneó el QR de ' . $this->pet->name)
            ->view('emails.pet_ping_owner', [
                'pet'    => $this->pet,
                'ping'   => $this->ping,
                'maps'   => $maps,
            ]);
    }
}
