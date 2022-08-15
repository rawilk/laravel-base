<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth\Passwords;

use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\Auth\CompletePasswordReset;
use Rawilk\LaravelBase\Contracts\Auth\FailedPasswordResetResponse;
use Rawilk\LaravelBase\Contracts\Auth\PasswordResetResponse;
use Rawilk\LaravelBase\Contracts\Auth\ResetPasswordViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\ResetsUserPasswords;
use Rawilk\LaravelBase\LaravelBase;

class Reset extends Component
{
    public string $token;

    public string $email = '';

    public string $password = '';

    public bool $needsEmail = true;

    public function resetPassword()
    {
        $this->resetErrorBag();

        $this->validate([
            'token' => ['required'],
            LaravelBase::email() => ['required', 'string', 'email'],
            'password' => ['required'], // Further validation should take place in the reset password action
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = $this->broker()->reset(
            [
                LaravelBase::email() => $this->email,
                'token' => $this->token,
                'password' => $this->password,
            ],
            function ($user) {
                app(ResetsUserPasswords::class)->reset(
                    $user,
                    [
                        'email' => $this->email,
                        'token' => $this->token,
                        'password' => $this->password,
                    ]
                );

                app(CompletePasswordReset::class)($this->guard(), $user);
            }
        );

        // If the status was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status === Password::PASSWORD_RESET
            ? app(PasswordResetResponse::class, ['status' => $status])->toResponse(request())
            : app(FailedPasswordResetResponse::class, ['status' => $status])->toResponse(request());
    }

    // $token will be set automatically by Livewire's magic
    public function mount(Request $request, string $token): void
    {
        if ($request->has('email')) {
            $this->email = $request->get('email');
            $this->needsEmail = false;
        }
    }

    public function render(): View
    {
        return app(ResetPasswordViewResponse::class)->toResponse(request());
    }

    protected function broker(): PasswordBroker
    {
        return Password::broker(
            Config::get('laravel-base.passwords')
        );
    }

    protected function guard(): StatefulGuard
    {
        return Auth::guard();
    }
}
