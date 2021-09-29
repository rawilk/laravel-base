<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\TwoFactor\DisableTwoFactorAuthenticationAction;
use Rawilk\LaravelBase\Actions\TwoFactor\EnableTwoFactorAuthenticationAction;
use Rawilk\LaravelBase\Actions\TwoFactor\GenerateNewRecoveryCodesAction;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Concerns\ConfirmsPasswords;

/**
 * @property-read \Illuminate\Contracts\Auth\Authenticatable $user
 * @property-read bool $enabled
 * @property-read bool $mustConfirmPassword
 */
class TwoFactorAuthenticationForm extends Component
{
    use ConfirmsPasswords;

    public bool $showingQrCode = false;
    public bool $showingRecoveryCodes = false;

    public function enableTwoFactorAuthentication(EnableTwoFactorAuthenticationAction $action): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $action(Auth::user());

        $this->showingQrCode = true;
        $this->showingRecoveryCodes = true;
    }

    public function showRecoveryCodes(): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->showingRecoveryCodes = true;
    }

    public function regenerateRecoveryCodes(GenerateNewRecoveryCodesAction $action): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $action(Auth::user());

        $this->showingRecoveryCodes = true;
    }

    public function disableTwoFactorAuthentication(DisableTwoFactorAuthenticationAction $action): void
    {
        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $action(Auth::user());
    }

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function getEnabledProperty(): bool
    {
        return ! empty($this->user->two_factor_secret);
    }

    public function getMustConfirmPasswordProperty(): bool
    {
        return Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword');
    }

    public function render(): View
    {
        return view('livewire.profile.two-factor-authentication-form');
    }
}
