<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionNotifyMail extends Mailable
{
    use Queueable, SerializesModels;
    public $amount;
    public $totalAmount;

    /**
     * Create a new message instance.
     */
    public function __construct($amount, $totalAmount)
    {
        $this->amount = $amount;
        $this->totalAmount = $totalAmount;
        //
    }

     public function build()
    {
        return $this->subject('Transaction Successful Notifictaion')
            ->view('templates.notify')
            ->with([
                'amount' => $this->amount,
                'totalAmount' => $this->totalAmount,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Transaction Notify Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'templates.notify',
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
