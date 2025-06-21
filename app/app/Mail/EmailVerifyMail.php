<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $userEmail;

    /**
     * Create a new message instance.
     */
    public function __construct($token, $userEmail)
    {
        //
        $this->token = $token;
        $this->userEmail = $userEmail;
    }

    public function build()
    {
        return $this->subject('Email Verify Mail')
            ->view('templates.mail')
            ->with([
                'email' => $this->userEmail,
                'otp' => $this->token,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Verify Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'templates.mail',
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
