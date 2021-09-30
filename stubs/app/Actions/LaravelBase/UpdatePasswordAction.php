<?php

declare(strict_types=1);

namespace App\Actions\LaravelBase;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserPasswords;

class UpdatePasswordAction implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    public function update($user, array $input, bool $updatingOtherUser = false)
    {
        // We'll only require a password confirmation when a user is updating their own password
        // on the profile page.
        $needsConfirm = ! $updatingOtherUser;

        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(needsConfirm: $needsConfirm),
        ])->after(function ($validator) use ($input) {
            if (! Hash::check($input['current_password'], Auth::user()->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => $input['password'],
        ])->save();
    }
}
