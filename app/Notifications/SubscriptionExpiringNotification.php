<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionExpiringNotification extends Notification
{
    use Queueable;

    public $daysLeft;

    public function __construct($daysLeft)
    {
        $this->daysLeft = $daysLeft;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Votre abonnement expire bientôt')
                    ->greeting('Bonjour ' . $notifiable->name)
                    ->line("Votre abonnement expirera dans {$this->daysLeft} jours.")
                    ->action('Renouveler maintenant', url('/subscription/renew'))
                    ->line('Merci de rester avec nous !');
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Votre abonnement expirera dans {$this->daysLeft} jours.",
            'days_left' => $this->daysLeft,
        ];
    }
}
