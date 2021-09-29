<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Rawilk\LaravelBase\Contracts\Auth\PasswordConfirmedResponse as PasswordConfirmedResponseContract;
use Rawilk\LaravelBase\LaravelBase;

class PasswordConfirmedResponse implements PasswordConfirmedResponseContract
{
    public function toResponse($request)
    {
        return redirect()->intended(LaravelBase::redirects('password-confirmation'));
    }
}
