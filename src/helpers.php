<?php

declare(strict_types=1);

use Carbon\Carbon;
use Rawilk\LaravelBase\LaravelBase;

if (! function_exists('appName')) {
    /**
     * Convenience method for getting the configured
     * application name.
     *
     * @return string|null
     */
    function appName(): null|string
    {
        return config('app.name');
    }
}

if (! function_exists('appIsLocal')) {
    /**
     * Convenience method for testing if the application
     * is in a local environment.
     *
     * @return bool
     */
    function appIsLocal(): bool
    {
        return (bool) app()->environment('local');
    }
}

if (! function_exists('minDateToUTC')) {
    /**
     * Convert a user specified date from the authenticated user's timezone
     * into a UTC equivalent for filtering from a minimum date to properly
     * filter by a date column stored in the UTC timezone.
     *
     * @param null|string|\Carbon\Carbon $date
     * @return \Carbon\CarbonInterface|null
     */
    function minDateToUTC($date): null|\Carbon\CarbonInterface
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
     * @return \Carbon\CarbonInterface|null
     */
    function maxDateToUTC($date): null|\Carbon\CarbonInterface
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

if (! function_exists('convertEmptyStringsToNull')) {
    /**
     * Convert any empty strings to null values from the given dataset.
     *
     * @param array $data
     * @return array
     */
    function convertEmptyStringsToNull(array $data): array
    {
        return array_map(static fn ($value) => $value === '' ? null : $value, $data);
    }
}

if (! function_exists('prefixSelectColumns')) {
    /**
     * Prefix the given columns with the given model's table name for a select statement.
     * Useful to avoid ambiguous select statements for polymorphic relationships.
     *
     * @param string $model
     * @param string|array|\Illuminate\Support\Collection ...$columns
     * @return string
     */
    function prefixSelectColumns(string $model, ...$columns): string
    {
        $table = app($model)->getTable();

        return collect($columns)
            ->flatten()
            ->map(fn (string $column) => "{$table}.{$column}")
            ->implode(',');
    }
}
