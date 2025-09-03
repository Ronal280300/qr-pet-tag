<?php
// app/Mail/OtpCodeMail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code) {}

    public function build()
    {
        return $this->subject('Tu código de verificación')
                    ->view('emails.otp', ['code' => $this->code]);
    }
}
