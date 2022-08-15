<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth\Passwords;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Rawilk\LaravelBase\Contracts\Auth\FailedPasswordResetLinkRequestResponse;
use Rawilk\LaravelBase\Contracts\Auth\RequestPasswordResetLinkViewResponse;
use Rawilk\LaravelBase\Contracts\Auth\SuccessfulPasswordResetLinkRequestResponse;
use Rawilk\LaravelBase\Http\Livewire\Concerns\ThrottlesAuthenticationAttempts;
use Rawilk\LaravelBase\LaravelBase;

class Email extends Component
{
    use ThrottlesAuthenticationAttempts;

    public string $email = '';

    public bool $emailSent = false;

    public function sendPasswordResetLink()
    {
        try {
            $this->rateLimit(static::$maxAttempts, static::$decaySeconds);
        } catch (TooManyRequestsException $e) {
            throw ValidationException::withMessages([
                LaravelBase::email() => [
                    __('auth.general_throttle', [
                        'seconds' => $e->secondsUntilAvailable,
                        'minutes' => $e->minutesUntilAvailable,
                    ]),
                ],
            ]);
        }

        $this->resetErrorBag();

        $this->validate([LaravelBase::email() => ['required', 'string', 'email']]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show the user. Finally, we'll send out a proper response.
        $status = $this->broker()->sendResetLink(
            [LaravelBase::email() => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSent = true;
        }

        return $status === Password::RESET_LINK_SENT
            ? app(SuccessfulPasswordResetLinkRequestResponse::class, ['status' => $status])->toResponse(request())
            : app(FailedPasswordResetLinkRequestResponse::class, ['status' => $status])->toResponse(request());
    }

    public function mount(): void
    {
        if (Auth::check()) {
            $this->email = Auth::user()->email;
        }
    }

    public function render()
    {
        return app(RequestPasswordResetLinkViewResponse::class)->toResponse(request());
    }

    protected function broker(): PasswordBroker
    {
        return Password::broker(
            Config::get('laravel-base.passwords')
        );
    }
}
