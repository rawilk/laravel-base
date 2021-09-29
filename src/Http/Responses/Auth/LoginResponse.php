<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Rawilk\LaravelBase\Contracts\Auth\LoginResponse as LoginResponseContract;
use Rawilk\LaravelBase\LaravelBase;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return redirect()->intended(LaravelBase::redirects('login'));
    }
}
