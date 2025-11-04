<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\EmailVerificationNotification;

class SendVerificationEmail implements ShouldQueue
{

    public function handle(UserRegistered $event): void
    {
        $event->user->notify(new EmailVerificationNotification());
    }
}
