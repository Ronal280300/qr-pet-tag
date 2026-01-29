<?php

namespace App\Mail;

use App\Models\Pet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PetInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pet;
    public $pets; // Array de mascotas (cuando son múltiples)
    public $activationUrl;
    public $planName;
    public $planPrice;
    public $petsCount;

    /**
     * Create a new message instance.
     * @param Pet $pet La mascota principal (la que tiene el token)
     * @param array|null $allPets Array con TODAS las mascotas del grupo (opcional)
     */
    public function __construct(Pet $pet, $allPets = null)
    {
        $this->pet = $pet;
        $this->pets = $allPets ?? collect([$pet]); // Si no se pasa array, usar solo la mascota principal
        $this->petsCount = is_array($allPets) ? count($allPets) : (is_countable($allPets) ? $allPets->count() : 1);
        $this->activationUrl = route('pet.activate', ['token' => $pet->pending_token]);

        // Información del plan
        if ($pet->pendingPlan) {
            $this->planName = $pet->pendingPlan->name;
            $this->planPrice = '₡' . number_format($pet->pendingPlan->price, 0, ',', '.');
        } else {
            $this->planName = 'Plan Standard';
            $this->planPrice = '₡0';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->petsCount > 1
            ? "🐾 Invitación para gestionar tus {$this->petsCount} mascotas"
            : '🐾 Invitación para gestionar tu mascota - ' . $this->pet->name;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pet-invitation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
