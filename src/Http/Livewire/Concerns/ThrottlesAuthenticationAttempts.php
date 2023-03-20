<?php

namespace Rawilk\LaravelBase\Http\Livewire\Concerns;

use DanHarrin\LivewireRateLimiting\WithRateLimiting;

trait ThrottlesAuthenticationAttempts
{
    use WithRateLimiting;

    /*
     * The maximum attempts a user may try attempting the action.
     */
    public static int $maxAttempts = 5;

    /*
     * The amount of seconds the user should be throttled from attempting
     * the action.
     */
    public static int $decaySeconds = 60;

    /**
     * Specify how many attempts are allowed for a user to try the action.
     */
    public static function maxAttemptsAllowed(int $attempts): void
    {
        static::$maxAttempts = $attempts;
    }

    /**
     * Specify the amount of seconds the user should be throttled for.
     */
    public static function secondsToThrottleFor(int $seconds): void
    {
        static::$decaySeconds = $seconds;
    }
}
