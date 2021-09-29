<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Rawilk\LaravelBase\Contracts\Auth\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Rawilk\LaravelBase\LaravelBase;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request)
    {
        return redirect()->intended(LaravelBase::redirects('login'));
    }
}
