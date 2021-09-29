<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    public function boot(): void
    {
        // if (Features::enabled(Features::emailVerification())) {
        //     $this->listen[Registered::class] = [
        //         SendEmailVerificationNotification::class,
        //     ];
        // }
    }
}
