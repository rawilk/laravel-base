<div>
    <x-card>
        <h3 class="text-lg leading-6 font-medium text-slate-900">{{ __('Delete Account') }}</h3>

        <div class="mt-2 max-w-xl text-sm text-gray-500">
            <p>{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please be sure this is what you want to do.') }}</p>
        </div>

        <div class="mt-5">
            <x-button variant="red" wire:click="confirmUserDeletion">
                {{ __('Delete Account') }}
            </x-button>
        </div>
    </x-card>

    @include('livewire.profile.partials.delete-user-dialog')
</div>
