<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;

class AuthenticatorAppPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, AuthenticatorApp $authenticatorApp): bool
    {
        return $this->edit($user, $authenticatorApp);
    }

    public function edit(User $user, AuthenticatorApp $authenticatorApp): bool
    {
        return $user->getAuthIdentifier() === $authenticatorApp->user_id;
    }
}
