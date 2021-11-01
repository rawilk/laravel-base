<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Rawilk\LaravelBase\Actions\Auth\LogoutAction;
use Rawilk\LaravelBase\Contracts\Auth\LogoutResponse;

class LogoutController
{
    public function __construct(protected LogoutAction $logoutAction)
    {
    }

    public function __invoke(Request $request)
    {
        $this->logoutAction->handle();

        return app(LogoutResponse::class);
    }
}
