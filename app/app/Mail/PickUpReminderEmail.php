<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PickUpReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $pickUpDate;
    public $title;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $pickUpDate, $title, $body)
    {
        //
        $this->email = $email;
        $this->pickUpDate = $pickUpDate;
        $this->title = $title;
        $this->body = $body;
    }

    public function build()
    {
        return $this->subject('Email Verify Mail')
            ->view('templates.mail')
            ->with([
                'email' => $this->email,
                'date' => $this->pickUpDate,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pick Up Reminder Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'templates.reminder',
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
