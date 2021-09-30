<x-dialog-modal wire:model.defer="confirmingLogout">
    <x-slot name="title">{{ __('Logout Other Browser Sessions') }}</x-slot>

    <x-slot name="content">
        <p>
            {{ __('Please enter your password to confirm you would like to logout of your other browser sessions across all of your devices.') }}
        </p>

        <div class="mt-4">
            <x-password
                wire:model.defer="password"
                wire:keydown.enter="logoutOtherBrowserSessions"
                name="password"
                placeholder="{{ __('Password') }}"
                focus
            />

            <x-form-error name="password" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-button
            wire:click="logoutOtherBrowserSessions"
            wire:target="logoutOtherBrowserSessions"
            variant="blue"
        >
            {{ __('Logout Other Browser Sessions') }}
        </x-button>

        <x-button
            wire:click="$set('confirmingLogout', false)"
            wire:loading.attr="disabled"
            variant="white"
        >
            {{ __('Nevermind') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
