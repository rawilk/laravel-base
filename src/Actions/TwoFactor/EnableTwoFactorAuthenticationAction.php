<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Actions\TwoFactor;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;
use Rawilk\LaravelBase\Contracts\Auth\TwoFactorAuthenticationProvider;
use Rawilk\LaravelBase\Contracts\Models\AuthenticatorApp;
use Rawilk\LaravelBase\Events\TwoFactorAuth\TwoFactorAuthEnabled;

class EnableTwoFactorAuthenticationAction
{
    public function __construct(protected TwoFactorAuthenticationProvider $provider)
    {
    }

    public function __invoke(User $user, string $twoFactorSecret, string|int $confirmationCode): void
    {
        $this->ensureCodeIsValid($twoFactorSecret, $confirmationCode);

        $authenticatorApp = tap(app(AuthenticatorApp::class)::make(), function (AuthenticatorApp $authenticatorApp) use ($user, $twoFactorSecret) {
            $authenticatorApp->forceFill([
                'name' => 'Authenticator app',
                'secret' => Crypt::encrypt($twoFactorSecret),
                'user_id' => $user->getAuthIdentifier(),
            ])->save();
        });

        event(new TwoFactorAuthEnabled($user, $authenticatorApp));
    }

    private function ensureCodeIsValid(string $twoFactorSecret, string|int $confirmationCode): void
    {
        $valid = $this->provider->verify($twoFactorSecret, (string) $confirmationCode);

        if (! $valid) {
            throw ValidationException::withMessages([
                'confirmationCode' => __('base::2fa.authenticator.alerts.invalid_code'),
            ]);
        }
    }
}
