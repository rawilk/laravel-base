<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Config;
use Rawilk\LaravelBase\LaravelBase;

class ConfirmPasswordAction
{
    public function __invoke(StatefulGuard $guard, $user, ?string $password = null): bool
    {
        $username = Config::get('laravel-base.username');

        return is_null(LaravelBase::$confirmPasswordsUsingCallback)
            ? $guard->validate([
                  $username => $user->{$username},
                  'password' => $password,
              ])
            : $this->confirmPasswordUsingCustomCallback($user, $password);
    }

    protected function confirmPasswordUsingCustomCallback($user, ?string $password = null): bool
    {
        return call_user_func(
            LaravelBase::$confirmPasswordsUsingCallback,
            $user,
            $password
        );
    }
}
