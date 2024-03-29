<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Str;

class CompletePasswordReset
{
    /**
     * Complete the password reset process for the given user.
     *
     * @param  mixed  $user
     */
    public function __invoke(StatefulGuard $guard, $user)
    {
        $user->setRememberToken(Str::random(60));

        $user->save();

        $guard->login($user);

        session()->regenerate();

        event(new PasswordReset($user));
    }
}
