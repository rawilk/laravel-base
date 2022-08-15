<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Rawilk\LaravelBase\Support\Auth\LoginRateLimiter;

class PrepareAuthenticatedSession
{
    public function __construct(protected LoginRateLimiter $limiter)
    {
    }

    /**
     * Prepare a new session for the new logged in user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        // Not using $request->session() to avoid errors during testing.
        session()->forget('login.id');
        session()->regenerate();

        $this->limiter->clear($request);

        return $next($request);
    }
}
