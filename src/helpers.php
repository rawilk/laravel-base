<?php

use Carbon\Carbon;
use Rawilk\LaravelBase\LaravelBase;

if (! function_exists('minDateToUTC')) {
    /**
     * Convert a user specified date from the authenticated user's timezone
     * into a UTC equivalent for filtering from a minimum date to properly
     * filter by a date column stored in the UTC timezone.
     *
     * @param null|string|\Carbon\Carbon $date
     * @return \Carbon\Carbon|null
     */
    function minDateToUTC($date): null|Carbon
    {
        if (! $date) {
            return null;
        }

        return Carbon::parse($date, userTimezone())->startOfDay()->utc();
    }
}

if (! function_exists('maxDateToUTC')) {
    /**
     * Convert a user specified date from the authenticated user's timezone
     * into a UTC equivalent for filtering from a maximum date to properly
     * filter by a date column stored in the UTC timezone.
     *
     * @param null|string|\Carbon\Carbon $date
     * @return \Carbon\Carbon|null
     */
    function maxDateToUTC($date): null|Carbon
    {
        if (! $date) {
            return null;
        }

        return Carbon::parse($date, userTimezone())->endOfDay()->utc();
    }
}

if (! function_exists('appTimezone')) {
    /**
     * Retrieve the configured application timezone.
     *
     * @return string
     */
    function appTimezone(): string
    {
        return is_null(LaravelBase::$findAppTimezoneUsingCallback)
            ? config('app.timezone')
            : call_user_func(LaravelBase::$findAppTimezoneUsingCallback);
    }
}

if (! function_exists('userTimezone')) {
    /**
     * Retrieve the authenticated user's timezone.
     * Fallback on the appTimezone if no authenticated user.
     *
     * @return string
     */
    function userTimezone(): string
    {
        $userTimezone = is_null(LaravelBase::$findUserTimezoneUsingCallback)
            ? auth()->user()?->timezone
            : call_user_func(LaravelBase::$findUserTimezoneUsingCallback, auth()->user());

        return $userTimezone ?? appTimezone();
    }
}
