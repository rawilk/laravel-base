<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\Auth\ConfirmPasswordAction;
use Rawilk\LaravelBase\Contracts\Auth\ConfirmPasswordViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\FailedPasswordConfirmationResponse;
use Rawilk\LaravelBase\Contracts\Auth\PasswordConfirmedResponse;

class ConfirmPassword extends Component
{
    public string $password = '';

    public function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    public function confirm()
    {
        $confirmed = app(ConfirmPasswordAction::class)(
            $this->guard(),
            Auth::user(),
            $this->password,
        );

        if ($confirmed) {
            Session::put('auth.password_confirmed_at', time());
        }

        return $confirmed
            ? app(PasswordConfirmedResponse::class)->toResponse(request())
            : app(FailedPasswordConfirmationResponse::class)->toResponse(request());
    }

    public function render(): View
    {
        return app(ConfirmPasswordViewResponse::class)->toResponse(request());
    }
}
