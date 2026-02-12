<?php

namespace App\Observers;

use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageReceivedNotification;

class MessageObserver
{
    public function created(Message $message)
    {
        $receiver = User::find($message->receiver_id);

        if ($receiver) {
            $receiver->notify(
                new MessageReceivedNotification(
                    $message->sender,
                    $message
                )
            );
        }
    }
}
