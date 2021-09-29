<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\Concerns\TwoFactorAuthenticatable;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\LaravelBase\Support\Auth\LoginRateLimiter;

class RedirectIfTwoFactorAuthenticatable
{
    public function __construct(protected StatefulGuard $guard, protected LoginRateLimiter $limiter)
    {
    }

    public function handle($request, $next)
    {
        $user = $this->validateCredentials($request);

        if (
            $user?->two_factor_secret
                 && in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user), true)
        ) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }

    protected function validateCredentials($request)
    {
        if (LaravelBase::$authenticateUsingCallback) {
            return tap(call_user_func(LaravelBase::$authenticateUsingCallback, $request), function ($user) use ($request) {
                if (! $user) {
                    $this->fireFailedEvent($request, $user);

                    $this->throwFailedAuthenticationException($request);
                }
            });
        }

        $model = $this->guard->getProvider()->getModel();

        return tap($model::where(LaravelBase::username(), $request->get(LaravelBase::username()))->first(), function ($user) use ($request) {
            if (! $user || ! $this->guard->getProvider()->validateCredentials($user, ['password' => $request->get('password')])) {
                $this->fireFailedEvent($request, $user);

                $this->throwFailedAuthenticationException($request);
            }
        });
    }

    protected function fireFailedEvent($request, $user = null): void
    {
        event(new Failed(Config::get('laravel-base.guard'), $user, [
            LaravelBase::username() => $request->get(LaravelBase::username()),
            'password' => $request->get('password'),
        ]));
    }

    protected function throwFailedAuthenticationException($request): void
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            LaravelBase::username() => [__('auth.failed')],
        ]);
    }

    protected function twoFactorChallengeResponse($request, $user)
    {
        $request->session()->put([
            'login.id' => $user->getKey(),
            'login.remember' => $request->boolean('remember'),
        ]);

        return redirect()->route('two-factor.login');
    }
}
