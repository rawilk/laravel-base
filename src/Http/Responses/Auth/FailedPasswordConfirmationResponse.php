<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\Contracts\Auth\FailedPasswordConfirmationResponse as FailedPasswordConfirmationResponseContract;

class FailedPasswordConfirmationResponse implements FailedPasswordConfirmationResponseContract
{
    public function toResponse($request)
    {
        throw ValidationException::withMessages([
            'password' => [__('auth.password')],
        ]);
    }
}
