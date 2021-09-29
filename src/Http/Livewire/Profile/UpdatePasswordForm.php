<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserPasswords;

class UpdatePasswordForm extends Component
{
    public array $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation',
    ];

    public function updatePassword(UpdatesUserPasswords $updater): void
    {
        $this->resetErrorBag();

        $updater->update(Auth::user(), $this->state);

        $this->reset('state');

        $this->emitSelf('saved');
    }

    public function render(): View
    {
        return view('livewire.profile.update-password-form');
    }
}
