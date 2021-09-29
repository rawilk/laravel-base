<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\TwoFactor;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Rawilk\LaravelBase\Services\RecoveryCode;

class GenerateNewRecoveryCodesAction
{
    public function __invoke($user)
    {
        $user->forceFill([
            'two_factor_recovery_codes' => Crypt::encrypt(
                json_encode(Collection::times(8, fn () => RecoveryCode::generate())->all())
            ),
        ])->save();
    }
}
