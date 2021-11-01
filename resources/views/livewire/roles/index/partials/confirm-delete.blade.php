<x-dialog-modal wire:model.defer="showDelete" max-width="md">
    <x-slot name="title">
        {{ __('laravel-base::roles.confirm_delete.title') }}
    </x-slot>

    <x-slot name="content">
        <p>{!! __('laravel-base::roles.confirm_delete.text', ['role' => $deleting?->name]) !!}</p>
    </x-slot>

    <x-slot name="footer">
        <x-button wire:click="deleteRole"
                  wire:target="deleteRole"
                  variant="red"
        >
            {{ __('laravel-base::messages.delete_button') }}
        </x-button>

        <x-button wire:click="$set('showDelete', false)"
                  wire:loading.attr="disabled"
                  variant="white"
        >
            {{ __('laravel-base::messages.confirm_modal_cancel') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
