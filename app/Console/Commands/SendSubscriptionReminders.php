<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\SubscriptionExpiringNotification;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'subscription:remind';
    protected $description = 'Send notification 7 days before subscription expires';

    public function handle()
    {
        $today = Carbon::today();
        $targetDate = $today->copy()->addDays(7);

        $users = User::whereDate('subscription_ends_at', $targetDate)->get();

        foreach ($users as $user) {
            $user->notify(new SubscriptionExpiringNotification(7));
        }

        $this->info('Subscription reminders sent!');
    }
}
