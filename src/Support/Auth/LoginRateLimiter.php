<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Support\Auth;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\LaravelBase;

class LoginRateLimiter
{
    /**
     * The maximum amount of attempts a user may try logging in
     * before getting throttled.
     */
    public static int $maxAttempts = 5;

    /**
     * The amount of seconds the user should be throttled from
     * attempting to login.
     */
    public static int $decaySeconds = 60;

    /**
     * The key that should be used for throttling login requests.
     *
     * @var callable|null
     */
    public static $keyBy;

    public function __construct(protected RateLimiter $limiter)
    {
    }

    /**
     * Specify a custom callback to use to generate the throttle key.
     *
     * @param callable $callback
     */
    public static function keyBy($callback): void
    {
        static::$keyBy = $callback;
    }

    /**
     * Specify the amount of seconds the user should be throttled for.
     *
     * @param int $seconds
     */
    public static function secondsToThrottleFor(int $seconds): void
    {
        static::$decaySeconds = $seconds;
    }

    /**
     * Specify how many attempts are allowed for a user to try and login.
     *
     * @param int $attempts
     */
    public static function maxAttemptsAllowed(int $attempts): void
    {
        static::$maxAttempts = $attempts;
    }

    /*
     * Get the number of attempts for the given key.
     */
    public function attempts(Request $request)
    {
        return $this->limiter->attempts($this->throttleKey($request));
    }

    /*
     * Determine if the user has too many failed login attempts.
     */
    public function tooManyAttempts(Request $request): bool
    {
        return $this->limiter->tooManyAttempts($this->throttleKey($request), static::$maxAttempts);
    }

    /*
     * Increment the login attempts for the user.
     */
    public function increment(Request $request): void
    {
        $this->limiter->hit($this->throttleKey($request), static::$decaySeconds);
    }

    /*
     * Determine the number of seconds until logging in is available again.
     */
    public function availableIn(Request $request): int
    {
        return $this->limiter->availableIn($this->throttleKey($request));
    }

    /*
     * Clear the login locks for the given user credentials.
     */
    public function clear(Request $request): void
    {
        $this->limiter->clear($this->throttleKey($request));
    }

    protected function throttleKey(Request $request): string
    {
        if (static::$keyBy) {
            return call_user_func(static::$keyBy, $request);
        }

        return Str::lower($request->input(LaravelBase::username())) . '|' . $request->ip();
    }
}
