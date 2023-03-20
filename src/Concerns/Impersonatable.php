<?php

namespace Rawilk\LaravelBase\Concerns;

use Illuminate\Contracts\Auth\Authenticatable as User;

trait Impersonatable
{
    /**
     * Determine if the user can impersonate another user.
     */
    public function canImpersonate(): bool
    {
        return false;
    }

    /**
     * Determine if the user can be impersonated.
     */
    public function canBeImpersonated(?User $impersonator = null): bool
    {
        return true;
    }

    /**
     * Get the name of the impersonator to put in the session.
     */
    public function nameForImpersonation(): ?string
    {
        return $this->name;
    }
}
