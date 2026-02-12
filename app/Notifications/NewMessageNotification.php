<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $senderName = $this->message->sender->name . ' ' . ($this->message->sender->prenom ?? '');

        return [
            'message' => $senderName . ' vous a envoyé un message.',
            'sender_id' => $this->message->sender_id,
            'sender_name' => $senderName,
            'conversation_id' => $this->message->conversation_id,
            'type' => 'new_message',
        ];
    }
}
