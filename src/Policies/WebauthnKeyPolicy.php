<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Rawilk\Webauthn\Contracts\WebauthnKey;

class WebauthnKeyPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, WebauthnKey $webauthnKey): bool
    {
        return $this->edit($user, $webauthnKey);
    }

    public function edit(User $user, WebauthnKey $webauthnKey): bool
    {
        return $user->getAuthIdentifier() === $webauthnKey->user_id;
    }
}
