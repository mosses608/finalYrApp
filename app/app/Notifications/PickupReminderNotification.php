<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PickupReminderNotification extends Notification
{
    use Queueable;
     public $pickup;

    /**
     * Create a new notification instance.
     */
    public function __construct($pickup)
    {
        //
        $this->pickup = $pickup;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

     public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'Pickup Reminder',
            'body' => "You have a scheduled pickup at {$this->pickup->location} on {$this->pickup->pickup_date} at {$this->pickup->preferred_time}.",
            'pickup_id' => $this->pickup->id,
        ]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
