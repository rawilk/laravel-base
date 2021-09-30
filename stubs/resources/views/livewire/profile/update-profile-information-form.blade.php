<div>
    <x-card>
        <x-slot name="header">
            <h2>{{ __('Profile Information') }}</h2>
            <p class="text-sm text-cool-gray-500">
                {{ __('Update your account\'s profile information and email address.') }}
            </p>
        </x-slot>

        <x-form wire:submit.prevent="updateProfileInformation" id="update-profile-form">
            <div>
                {{-- avatar --}}
                @includeWhen(\Rawilk\LaravelBase\Features::managesAvatars(), 'livewire.users.partials.user-avatar-upload')

                {{-- name --}}
                <x-form-group label="{{ __('Name') }}" name="name" inline>
                    <x-input
                        wire:model.defer="state.name"
                        name="name"
                        required
                        autocomplete="name"
                        maxlength="255"
                        max-width=" sm:max-w-sm"
                    />
                </x-form-group>

                {{-- email --}}
                <x-form-group label="{{ __('Email address') }}" name="email" inline>
                    <x-email
                        wire:model.defer="state.email"
                        name="email"
                        required
                        maxlength="255"
                    />
                </x-form-group>

                {{-- timezone --}}
                <x-form-group label="{{ __('Timezone') }}" name="timezone" inline>
                    <x-timezone-select
                        wire:model.defer="state.timezone"
                        name="timezone"
                        required
                        use-custom-select
                        fixed-position
                        :only="timezoneSubsets()"
                    />
                </x-form-group>
            </div>
        </x-form>

        <x-slot name="footer">
            <div class="flex items-center justify-end space-x-4">
                <x-action-message on="profile.updated" />

                <x-button type="submit" variant="blue" form="update-profile-form" wire:target="updateProfileInformation">
                    <span>{{ __('Save') }}</span>
                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot>
    </x-card>
</div>
