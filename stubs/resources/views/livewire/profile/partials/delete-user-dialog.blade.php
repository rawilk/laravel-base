<x-dialog-modal wire:model.defer="confirmingUserDeletion">
    <x-slot name="title">{{ __('Delete Account') }}</x-slot>

    <x-slot name="content">
        <p>
            {{ __(
            'Are you sure you want to delete your account? Once your account is deleted,
             all of its resources and data will be permanently deleted. Please enter
             your password to confirm you would like to permanently delete your account.'
            ) }}
        </p>

        <div class="mt-4">
            <x-password
                wire:model.defer="password"
                wire:keydown.enter="deleteUser"
                name="password"
                placeholder="{{ __('Password') }}"
                focus
            />

            <x-form-error name="password" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-button
            wire:click="deleteUser"
            wire:target="deleteUser"
            variant="red"
        >
            {{ __('Delete Account') }}
        </x-button>

        <x-button
            wire:click="$set('confirmingUserDeletion', false)"
            wire:loading.attr="disabled"
            variant="white"
        >
            {{ __('Nevermind') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
