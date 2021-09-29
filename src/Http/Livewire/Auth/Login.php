<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\Auth\AttemptToAuthenticate;
use Rawilk\LaravelBase\Actions\Auth\EnsureLoginIsNotThrottled;
use Rawilk\LaravelBase\Actions\Auth\PrepareAuthenticatedSession;
use Rawilk\LaravelBase\Actions\Auth\RedirectIfTwoFactorAuthenticatable;
use Rawilk\LaravelBase\Contracts\Auth\LoginResponse;
use Rawilk\LaravelBase\Contracts\Auth\LoginViewResponse;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\LaravelBase;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login(Request $request)
    {
        $credentials = $this->validate([
            LaravelBase::username() => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        $request->merge($credentials);

        return $this->loginPipeline($request)
            ->then(fn () => app(LoginResponse::class)->toResponse($request));
    }

    protected function loginPipeline($request): Pipeline
    {
        if (LaravelBase::$authenticateThroughCallback) {
            return app(Pipeline::class)
                ->send($request)
                ->through(array_filter(
                    call_user_func(LaravelBase::$authenticateThroughCallback, $request)
                ));
        }

        return app(Pipeline::class)
            ->send($request)
            ->through(array_filter([
                EnsureLoginIsNotThrottled::class,
                Features::canManageTwoFactorAuthentication() ? RedirectIfTwoFactorAuthenticatable::class : null,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
            ]));
    }

    public function render(): View
    {
        return app(LoginViewResponse::class)->toResponse(request());
    }
}
