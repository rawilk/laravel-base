<?php

declare(strict_types=1);

namespace App\Listeners\Users;

use App\Notifications\Users\WelcomeNotification;
use Illuminate\Auth\Events\Registered;

final class RegisteredListener
{
    public function handle(Registered $event): void
    {
        $event->user->notify(new WelcomeNotification);
    }
}
