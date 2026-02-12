<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusNotification extends Notification
{
    use Queueable;

    protected Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->application->status === 'accepted' ? 'acceptée' : 'refusée';

        return [
            'message' => 'Votre candidature pour "' . $this->application->offre->titre . '" a été ' . $status . '.',
            'offre_id' => $this->application->offre_id,
            'application_id' => $this->application->id,
            'status' => $this->application->status,
            'type' => 'application_status',
        ];
    }
}
