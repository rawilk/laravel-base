<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Rawilk\LaravelBase\Actions\TwoFactor\GenerateNewRecoveryCodesAction;
use Rawilk\LaravelBase\Features;
use Rawilk\LaravelBase\Http\Livewire\Concerns\ConfirmsPasswords;

/**
 * @property-read bool $mustConfirmPassword
 */
class TwoFactorRecoveryCodes extends Component
{
    use ConfirmsPasswords;

    public User $user;
    public bool $enabled;
    public bool $showingCodes = false;

    protected $listeners = [
        'two-factor-updated' => 'updateStatus',
    ];

    public function getMustConfirmPasswordProperty(): bool
    {
        return Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            || Features::optionEnabled(Features::webauthn(), 'confirmPassword');
    }

    public function updateStatus()
    {
        $enabled = $this->user->two_factor_enabled;

        if ($enabled !== $this->enabled && $enabled) {
            $this->showingCodes = true;
        }

        $this->enabled = $enabled;
    }

    public function showCodes(): void
    {
        if (! $this->enabled) {
            return;
        }

        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->showingCodes = true;
    }

    public function regenerate(GenerateNewRecoveryCodesAction $generator): void
    {
        if (! $this->user->two_factor_enabled) {
            return;
        }

        if ($this->mustConfirmPassword) {
            $this->ensurePasswordIsConfirmed();
        }

        $generator($this->user);
    }

    public function mount(): void
    {
        $this->enabled = $this->user->two_factor_enabled;
    }

    public function render(): View
    {
        return view('livewire.profile.2fa-recovery-codes');
    }
}
