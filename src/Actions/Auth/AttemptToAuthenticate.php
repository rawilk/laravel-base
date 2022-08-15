<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\LaravelBase;
use Rawilk\LaravelBase\Support\Auth\LoginRateLimiter;

class AttemptToAuthenticate
{
    public function __construct(protected StatefulGuard $guard, protected LoginRateLimiter $limiter)
    {
    }

    /**
     * Attempt to authenticate a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle($request, $next)
    {
        if (LaravelBase::$authenticateUsingCallback) {
            return $this->handleUsingCustomCallback($request, $next);
        }

        if ($this->guard->attempt(
            $request->only(LaravelBase::username(), 'password'),
            $request->boolean('remember'),
        )) {
            return $next($request);
        }

        $this->throwFailedAuthenticationException($request);
    }

    /**
     * Attempt to authenticate using a custom callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    protected function handleUsingCustomCallback($request, $next)
    {
        $user = call_user_func(LaravelBase::$authenticateUsingCallback, $request);

        if (! $user) {
            $this->fireFailedEvent($request);

            return $this->throwFailedAuthenticationException($request);
        }

        $this->guard->login($user, $request->boolean('remember'));

        return $next($request);
    }

    protected function throwFailedAuthenticationException($request): void
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages([
            LaravelBase::username() => [__('auth.failed')],
        ]);
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    protected function fireFailedEvent($request): void
    {
        event(new Failed(Config::get('laravel-base.guard'), null, [
            LaravelBase::username() => $request->get(LaravelBase::username()),
            'password' => $request->get('password'),
        ]));
    }
}
