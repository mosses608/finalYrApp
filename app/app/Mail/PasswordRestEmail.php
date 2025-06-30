<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;

class PasswordRestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $residentEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $residentEmail)
    {
        $this->otp = $otp;
        $this->residentEmail = $residentEmail;
    }

    public function build()
    {
        return $this->subject('Password Reset Request')
                    ->view('templates.reset_password')
                    ->with([
                        'email' => $this->residentEmail,
                        'token' => $this->otp,
                        'resetLink' => url('/change-password?otp=' . Crypt::encrypt($this->otp) . '&email=' . urlencode($this->residentEmail)),
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Rest Email',
        );
    }
    

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'templates.reset-password',
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
