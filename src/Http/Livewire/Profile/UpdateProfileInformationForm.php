<?php

declare(strict_types=1);

namespace Rawilk\LaravelBase\Http\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Rawilk\LaravelBase\Components\Alerts\Alert;
use Rawilk\LaravelBase\Contracts\Profile\UpdatesUserProfileInformation;

/**
 * @property-read \Illuminate\Contracts\Auth\Authenticatable $user
 */
class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public array $state = [];
    public $photo;

    public function getUserProperty()
    {
        return Auth::user();
    }

    public function updatedPhoto(): void
    {
        $this->validateOnly('photo', [
            'photo' => ['mimes:jpg,jpeg,png', 'max:1024'],
        ]);
    }

    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $updater->update(
            Auth::user()->fresh(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            // Photo has to be reset, otherwise flash message will be lost on redirect...
            $this->photo = null;

            return redirect()->route('profile.show')
                ->with(Alert::SUCCESS, __('Profile information updated successfully!'));
        }

        $this->emitSelf('profile.updated');
        $this->emit('refresh-navigation-menu');
    }

    public function cancelUpload(): void
    {
        $this->photo = null;
        $this->resetErrorBag('photo');
    }

    public function deleteProfilePhoto(): void
    {
        Auth::user()->deleteAvatar();

        $this->emitSelf('profile.updated');
        $this->emit('refresh-navigation-menu');
    }

    public function mount(): void
    {
        $this->state = Auth::user()->withoutRelations()->toArray();
        $this->state['name'] = Auth::user()->name->full;
    }

    public function render(): View
    {
        return view('livewire.profile.update-profile-information-form');
    }
}
