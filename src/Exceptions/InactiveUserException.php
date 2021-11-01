<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Exceptions;

use Exception;
use Illuminate\Contracts\View\View;
use Rawilk\LaravelBase\Actions\Auth\LogoutAction;

class InactiveUserException extends Exception
{
    public function render(): View
    {
        app(LogoutAction::class)->handle();

        return view('errors.inactive-user');
    }

    public function report(): bool
    {
        return false;
    }
}
