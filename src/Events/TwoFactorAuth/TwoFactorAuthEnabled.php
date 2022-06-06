<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\TwoFactorAuth;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Queue\SerializesModels;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;

class TwoFactorAuthEnabled
{
    use SerializesModels;

    public function __construct(public User $user, public AuthenticatorApp $authenticatorApp)
    {
    }
}
