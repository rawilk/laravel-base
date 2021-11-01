<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;

class LogoutAction
{
    public function __construct(protected StatefulGuard $guard)
    {
    }

    public function handle(): void
    {
        $this->guard->logout();

        session()->invalidate();

        session()->regenerateToken();
    }
}
