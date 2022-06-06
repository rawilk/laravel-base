<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\TwoFactor;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Rawilk\LaravelBase\Services\RecoveryCode;

class MarkTwoFactorEnabledAction
{
    public function __invoke(User $user): void
    {
        if ($user->two_factor_enabled) {
            return;
        }

        $user->forceFill([
            'two_factor_enabled' => true,
            'two_factor_recovery_codes' => Crypt::encrypt(
                json_encode(Collection::times(8, fn () => RecoveryCode::generate())->all())
            ),
        ])->save();
    }
}
