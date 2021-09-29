<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Rawilk\LaravelBase\Contracts\Auth\RegisterResponse as RegisterResponseContract;
use Rawilk\LaravelBase\LaravelBase;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return redirect()->intended(LaravelBase::redirects('register'));
    }
}
