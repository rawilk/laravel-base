<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\TwoFactor;

class DisableTwoFactorAuthenticationAction
{
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();
    }
}
