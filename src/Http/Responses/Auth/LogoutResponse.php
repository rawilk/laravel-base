<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Responses\Auth;

use Rawilk\LaravelBase\Contracts\Auth\LogoutResponse as LogoutResponseContract;
use Rawilk\LaravelBase\LaravelBase;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        return redirect(LaravelBase::redirects('logout', '/'));
    }
}
