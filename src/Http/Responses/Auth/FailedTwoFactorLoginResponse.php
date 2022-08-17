<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\Contracts\Auth\FailedTwoFactorLoginResponse as FailedTwoFactorLoginResponseContract;

class FailedTwoFactorLoginResponse implements FailedTwoFactorLoginResponseContract
{
    public function toResponse($request)
    {
        throw ValidationException::withMessages([
            'two_factor' => [__('base::2fa.challenge.alerts.invalid')],
        ]);
    }
}
