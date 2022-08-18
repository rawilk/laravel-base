<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Services\Auth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class CustomSessionGuard extends SessionGuard
{
    /**
     * Log a user in the application without firing the Login event.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function quietLogin(Authenticatable $user, $remember = false): void
    {
        $this->updateSession($user->getAuthIdentifier());

        /*
         * If the user should be permanently "remembered" by the application we will
         * queue a permanent cookie that contains the encrypted copy of the user
         * identifier. We will then decrypt this later to retrieve the users.
         */
        if ($remember) {
            $this->ensureRememberTokenIsSet($user);

            $this->queueRecallerCookie($user);
        }

        $this->setUser($user);
    }
}
