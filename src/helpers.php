<?php

declare(strict_types=1);

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Contracts\Enums\HasLabel;
use Rawilk\LaravelBase\LaravelBase;

if (! function_exists('appName')) {
    /**
     * Convenience method for getting the configured
     * application name.
     *
     * @return string|null
     */
    function appName(): ?string
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
     * @param  null|string|\Carbon\Carbon  $date
     * @return \Carbon\CarbonInterface|null
     */
    function minDateToUTC($date): ?Carbon\CarbonInterface
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
     * @param  null|string|\Carbon\Carbon  $date
     * @return \Carbon\CarbonInterface|null
     */
    function maxDateToUTC($date): ?Carbon\CarbonInterface
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
     * @param  null|\Illuminate\Contracts\Auth\Authenticatable  $user
     * @return string
     */
    function userTimezone(?Illuminate\Contracts\Auth\Authenticatable $user = null): string
    {
        $user = $user ?: auth()->user();

        $userTimezone = is_null(LaravelBase::$findUserTimezoneUsingCallback)
            ? $user?->timezone
            : call_user_func(LaravelBase::$findUserTimezoneUsingCallback, $user);

        return $userTimezone ?? appTimezone();
    }
}

if (! function_exists('enumToSelectOptions')) {
    /**
     * Map the given enum to an array of elements suitable for
     * a select field.
     *
     * @param  string  $enum
     * @param  string  $valueField
     * @param  string  $labelField
     * @return array
     */
    function enumToSelectOptions(string $enum, string $valueField = 'id', string $labelField = 'name'): array
    {
        return collect($enum::cases())
            ->map(function ($case) use ($valueField, $labelField) {
                return [
                    $valueField => $case->value,
                    $labelField => $case instanceof HasLabel ? $case->label() : $case->name,
                ];
            })
            ->values()
            ->toArray();
    }
}

if (! function_exists('enumToValues')) {
    /**
     * Generate an array of all the values from a given enum.
     *
     * @param  string  $enum
     * @return array
     */
    function enumToValues(string $enum): array
    {
        return array_map(fn ($case) => $case->value, $enum::cases());
    }
}

if (! function_exists('enumToLabels')) {
    /**
     * Generate an array of all the labels from a given enum.
     *
     * @param  string  $enum
     * @return array
     */
    function enumToLabels(string $enum): array
    {
        return array_map(function ($case) {
            return $case instanceof HasLabel
                ? $case->label()
                : $case->name;
        }, $enum::cases());
    }
}

if (! function_exists('convertEmptyStringsToNull')) {
    /**
     * Convert any empty strings to null values from the given dataset.
     *
     * @param  array  $data
     * @return array
     */
    function convertEmptyStringsToNull(array $data): array
    {
        return array_map(static fn ($value) => $value === '' ? null : $value, $data);
    }
}

if (! function_exists('pageTitle')) {
    /**
     * Format the given title segments into a single string for the <title> tag.
     *
     * @param  mixed  ...$segments
     * @return string
     */
    function pageTitle(...$segments): string
    {
        return collect($segments)->flatten()->implode(' | ');
    }
}

if (! function_exists('bladeRender')) {
    function bladeRender(string $blade, array $data = []): string
    {
        return Blade::render($blade, $data);
    }
}

if (! function_exists('isImpersonating')) {
    /**
     * Determine if a user is currently being impersonated.
     *
     * @return bool
     */
    function isImpersonating(): bool
    {
        return session()->has('impersonate');
    }
}

if (! function_exists('realUserId')) {
    /**
     * Return the correct authenticated user's id depending on
     * if a user is being impersonated or not.
     *
     * @return null|int
     */
    function realUserId(): ?int
    {
        return isImpersonating()
            ? (int) session()->get('impersonate')
            : auth()->id();
    }
}

if (! function_exists('prefixSelectColumns')) {
    /**
     * Prefix the given columns with the given model's table name for a select statement.
     * Useful to avoid ambiguous select statements for polymorphic relationships.
     *
     * @param  string  $model
     * @param  string|array|\Illuminate\Support\Collection  ...$columns
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

if (! function_exists('isExternalLink')) {
    /**
     * Determine if the given url is an external url (i.e. not the app's url).
     *
     * @param  string|null  $url
     * @return bool
     */
    function isExternalLink(?string $url): bool
    {
        // This is probably a relative link.
        if (Str::startsWith($url, ['#', '/'])) {
            return false;
        }

        $parsed = parse_url($url);
        $parsedSiteUrl = parse_url(config('app.url'));

        return ($parsed['host'] ?? '') !== ($parsedSiteUrl['host'] ?? '');
    }
}
