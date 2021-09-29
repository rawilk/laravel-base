<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Rawilk\LaravelBase\Contracts\Auth\LogoutResponse;

class LogoutController
{
    public function __construct(protected StatefulGuard $guard)
    {
    }

    public function __invoke(Request $request)
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
