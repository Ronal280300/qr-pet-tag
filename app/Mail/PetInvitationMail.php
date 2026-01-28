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
    public $activationUrl;
    public $planName;
    public $planPrice;

    /**
     * Create a new message instance.
     */
    public function __construct(Pet $pet)
    {
        $this->pet = $pet;
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
        return new Envelope(
            subject: '🐾 Invitación para gestionar tu mascota - ' . $this->pet->name,
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
