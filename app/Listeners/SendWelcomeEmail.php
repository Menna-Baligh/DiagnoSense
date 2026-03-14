<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Notifications\RegisterNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail implements ShouldQueue
{
    public $delay = 60;

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $event->user->notify(new RegisterNotification);
    }
}
