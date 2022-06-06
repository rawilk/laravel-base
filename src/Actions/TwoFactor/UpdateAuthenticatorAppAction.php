<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\TwoFactor;

use Illuminate\Support\Facades\Validator;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Events\TwoFactorAuth\AuthenticatorAppWasUpdated;

class UpdateAuthenticatorAppAction
{
    public function __invoke(AuthenticatorApp $authenticatorApp, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validate();

        $authenticatorApp->forceFill([
            'name' => $input['name'],
        ])->save();

        if ($authenticatorApp->wasChanged()) {
            event(new AuthenticatorAppWasUpdated($authenticatorApp));
        }
    }
}
