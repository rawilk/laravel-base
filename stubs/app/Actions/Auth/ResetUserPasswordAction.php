<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\LaravelBase\PasswordValidationRules;
use Illuminate\Support\Facades\Validator;
use Rawilk\LaravelBase\Contracts\Auth\ResetsUserPasswords;

class ResetUserPasswordAction implements ResetsUserPasswords
{
    use PasswordValidationRules;

    public function reset($user, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(needsConfirm: false),
        ])->validate();

        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}
