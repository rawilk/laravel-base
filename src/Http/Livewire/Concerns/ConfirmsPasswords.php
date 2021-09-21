<?php

namespace Rawilk\LaravelBase\Http\Livewire\Concerns;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

/** @mixin \Livewire\Component */
trait ConfirmsPasswords
{
    /*
     * Indicates if the user's password is being confirmed.
     */
    public bool $confirmingPassword = false;

    /*
     * The ID of the operation being confirmed.
     */
    public null|string $confirmableId = null;

    /*
     * The user's password.
     */
    public string $confirmablePassword = '';

    public function startConfirmingPassword(string $confirmableId): void
    {
        $this->resetErrorBag();

        if ($this->passwordIsConfirmed()) {
            $this->dispatchBrowserEvent('password-confirmed', [
                'id' => $confirmableId,
            ]);

            return;
        }

        $this->confirmingPassword = true;
        $this->confirmableId = $confirmableId;
        $this->confirmablePassword = '';

        $this->dispatchBrowserEvent('confirming-password');
    }

    public function stopConfirmingPassword(): void
    {
        $this->confirmingPassword = false;
        $this->confirmableId = null;
        $this->confirmablePassword = '';
    }

    public function confirmPassword(): void
    {
        $this->validate([
            'confirmablePassword' => ['required', 'string', 'password'],
        ], [
            'required' => __('laravel-base::validation.confirms_password.required'),
            'password' => __('laravel-base::validation.confirms_password.password'),
        ]);

        Session::put('auth.password_confirmed_at', time());

        $this->dispatchBrowserEvent('password-confirmed', [
            'id' => $this->confirmableId,
        ]);

        $this->stopConfirmingPassword();
    }

    protected function ensurePasswordIsConfirmed(null|int $maximumSecondsSinceConfirmation = null): void
    {
        abort_unless(
            $this->passwordIsConfirmed($maximumSecondsSinceConfirmation),
            Response::HTTP_FORBIDDEN,
        );
    }

    protected function passwordIsConfirmed(null|int $maximumSecondsSinceConfirmation = null): bool
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: Config::get('auth.password_timeout', 900);

        return (time() - Session::get('auth.password_confirmed_at', 0)) < $maximumSecondsSinceConfirmation;
    }
}
