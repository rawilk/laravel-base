<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Rawilk\LaravelBase\Contracts\Profile\DeletesUsers;

class DeleteUserForm extends Component
{
    public bool $confirmingUserDeletion = false;

    /*
     * The user's current password.
     */
    public string $password = '';

    /**
     * Confirm that the user would like to delete their account.
     */
    public function confirmUserDeletion(): void
    {
        $this->resetErrorBag();

        $this->password = '';

        $this->dispatchBrowserEvent('confirming-delete-user');

        $this->confirmingUserDeletion = true;
    }

    public function deleteUser(Request $request, DeletesUsers $deleter, StatefulGuard $auth)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not  match our records.')],
            ]);
        }

        $deleter->delete(Auth::user()->fresh());

        $auth->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        redirect('/');
    }

    public function render(): View
    {
        return view('livewire.profile.delete-user-form');
    }
}
