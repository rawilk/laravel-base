<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp as AuthenticatorAppContract;

class AuthenticatorApp extends Model implements AuthenticatorAppContract
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = [
        'secret',
    ];

    protected $casts = [
        'last_used_at' => 'immutable_datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('laravel-base.authenticator_apps.table');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function createdAtHtml(string $timezone = 'UTC'): string
    {
        $date = $this->created_at?->clone()->tz($timezone);

        return <<<HTML
        <time datetime="{$date?->toDateTimeString()}">{$date?->format('M d Y, g:i a')}</time>
        HTML;
    }

    public function lastUsedAtHtml(string $timezone = 'UTC'): string
    {
        $date = $this->last_used_at?->clone()->tz($timezone);

        if (! $date) {
            return __('base::2fa.authenticator.app_never_used');
        }

        return <<<HTML
        <time datetime="{$date->toDateTimeString()}">{$date->format('M d Y, g:i a')}</time>
        HTML;
    }
}
