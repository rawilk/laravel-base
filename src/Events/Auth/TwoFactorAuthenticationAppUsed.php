<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\Auth;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;

class TwoFactorAuthenticationAppUsed extends TwoFactorAuthenticationEvent
{
    public function __construct(public User $user, public AuthenticatorApp $authenticatorApp)
    {
        parent::__construct($user);
    }
}
