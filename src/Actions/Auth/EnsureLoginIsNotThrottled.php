<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Illuminate\Auth\Events\Lockout;
use Rawilk\LaravelBase\Contracts\Auth\LockoutResponse;
use Rawilk\LaravelBase\Support\Auth\LoginRateLimiter;

class EnsureLoginIsNotThrottled
{
    public function __construct(protected LoginRateLimiter $limiter)
    {
    }

    /**
     * Throttle the login requests if the user has attempted to login
     * too many times and failed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        if (! $this->limiter->tooManyAttempts($request)) {
            return $next($request);
        }

        event(new Lockout($request));

        return app(LockoutResponse::class)->toResponse($request);
    }
}
