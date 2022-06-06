<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\TwoFactor;

use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Events\TwoFactorAuth\AuthenticatorAppWasDeleted;

class DeleteAuthenticatorAppAction
{
    public function __invoke(AuthenticatorApp $authenticatorApp): void
    {
        $authenticatorApp->delete();

        event(new AuthenticatorAppWasDeleted($authenticatorApp));
    }
}
