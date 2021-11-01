<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController
{
    public function __invoke(): RedirectResponse
    {
        abort_unless(Session::has('impersonate'), Response::HTTP_FORBIDDEN);

        Auth::loginUsingId(Session::pull('impersonate'));

        /** @psalm-suppress UndefinedFunction */
        return redirect()->to(homeRoute());
    }
}
