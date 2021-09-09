<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase;

/**
 * This class is heavily inspired by the Fortify class in laravel/fortify.
 */
class LaravelBase
{
    /**
     * The callback that is responsible for retrieving the configured app timezone.
     *
     * @var callable|null
     */
    public static $findAppTimezoneUsingCallback;

    /**
     * The callback that is responsible for retrieving the authenticated user's timezone.
     *
     * @var callable|null
     */
    public static $findUserTimezoneUsingCallback;

    /**
     * Register a callback that is responsible for retrieving the configured app timezone.
     *
     * @param callable $callback
     */
    public static function findAppTimezoneUsing(callable $callback): void
    {
        static::$findAppTimezoneUsingCallback = $callback;
    }

    /**
     * Register a callback that is responsible for retrieving the authenticated user's timezone.
     *
     * @param callable $callback
     */
    public static function findUserTimezoneUsing(callable $callback): void
    {
        static::$findUserTimezoneUsingCallback = $callback;
    }
}
