<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Events\TwoFactorAuth;

use Illuminate\Queue\SerializesModels;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;

class AuthenticatorAppWasUpdated
{
    use SerializesModels;

    public function __construct(public AuthenticatorApp $authenticatorApp)
    {
    }
}
