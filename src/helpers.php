<?php

declare(strict_types=1);

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Rawilk\LaravelBase\Contracts\Enums\HasLabel;
use Rawilk\LaravelBase\Contracts\Models\ImpersonatesUsers;
use Rawilk\LaravelBase\LaravelBase;

if (! function_exists('appName')) {
    /**
     * Convenience method for getting the configured
     * application name.
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
     */
    function minDateToUTC($date): ?CarbonInterface
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
     */
    function maxDateToUTC($date): ?CarbonInterface
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
     */
    function userTimezone(Illuminate\Contracts\Auth\Authenticatable $user = null): string
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
     */
    function enumToSelectOptions(string $enum, string $valueField = 'id', string $labelField = 'name'): array
    {
        return collect($enum::cases())
            ->map(function ($case) use ($valueField, $labelField) {
                $label = $case instanceof HasLabel || method_exists($case, 'label')
                    ? $case->label()
                    : $case->name;

                return [
                    $valueField => $case->value,
                    $labelField => $label,
                ];
            })
            ->values()
            ->toArray();
    }
}

if (! function_exists('enumToValues')) {
    /**
     * Generate an array of all the values from a given enum.
     */
    function enumToValues(string $enum): array
    {
        return array_map(fn ($case) => $case->value, $enum::cases());
    }
}

if (! function_exists('enumToLabels')) {
    /**
     * Generate an array of all the labels from a given enum.
     */
    function enumToLabels(string $enum): array
    {
        return array_map(function ($case) {
            return $case instanceof HasLabel || method_exists($case, 'label')
                ? $case->label()
                : $case->name;
        }, $enum::cases());
    }
}

if (! function_exists('convertEmptyStringsToNull')) {
    /**
     * Convert any empty strings to null values from the given dataset.
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
     */
    function isImpersonating(): bool
    {
        return app(ImpersonatesUsers::class)->impersonating(request());
    }
}

if (! function_exists('realUserId')) {
    /**
     * Return the correct authenticated user's id depending on
     * if a user is being impersonated or not.
     */
    function realUserId(): mixed
    {
        return isImpersonating()
            ? app(ImpersonatesUsers::class)->impersonatorId(request())
            : auth()->id();
    }
}

if (! function_exists('getModelForGuard')) {
    /**
     * Get the user model used for a given guard.
     *
     * We have this function here just in-case spatie/laravel-permissions is not installed.
     */
    function getModelForGuard(string $guard): ?string
    {
        return collect(config('auth.guards'))
            ->map(function ($guard) {
                if (! isset($guard['provider'])) {
                    return;
                }

                return config("auth.providers.{$guard['provider']}.model");
            })->get($guard);
    }
}

if (! function_exists('prefixSelectColumns')) {
    /**
     * Prefix the given columns with the given model's table name for a select statement.
     * Useful to avoid ambiguous select statements for polymorphic relationships.
     *
     * @param  string|array|\Illuminate\Support\Collection  ...$columns
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
