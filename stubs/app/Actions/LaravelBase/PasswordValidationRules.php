<?php

namespace App\Actions\LaravelBase;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    protected function passwordRules(bool $optional = false, bool $needsConfirm = true): array
    {
        return [
            $optional ? 'nullable' : 'required',
            'string',
            Password::defaults(),
            $needsConfirm ? 'confirmed' : null,
        ];
    }
}
