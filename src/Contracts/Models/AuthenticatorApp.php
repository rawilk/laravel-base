<?php

namespace Rawilk\LaravelBase\Contracts\Models;

interface AuthenticatorApp
{
    /**
     * Return the date the authenticator app was created wrapped in a <time>
     * HTML tag.
     */
    public function createdAtHtml(string $timezone = 'UTC'): string;

    /**
     * Return the date the authenticator app was last used wrapped in
     * a <time> HTML tag.
     */
    public function lastUsedAtHtml(string $timezone = 'UTC'): string;
}
