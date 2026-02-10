<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FriendRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $sender;

    public function __construct($sender)
    {
        $this->sender = $sender;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouvelle demande d\'ami')
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line($this->sender->name . ' souhaite vous ajouter en ami.')
                    ->action('Voir les demandes', url('/network'))
                    ->line('Merci d\'utiliser Talentia !');
    }

    // toDatabase
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->sender->name . ' vous a envoyé une demande d\'ami.',
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
        ];
    }
}
