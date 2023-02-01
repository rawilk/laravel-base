<x-dialog-modal wire:model.defer="showDeleteAll" max-width="md">
    <x-slot name="title">{{ __('base::roles.confirm_bulk_delete.title') }}</x-slot>

    <x-slot name="content">
        <p>{!! __('base::roles.confirm_bulk_delete.text') !!}</p>
    </x-slot>

    <x-slot name="footer">
        <x-blade::button.button
            wire:click="deleteSelected"
            color="red"
        >
            {{ __('base::messages.delete_button') }}
        </x-blade::button.button>

        <x-blade::button.button
            wire:click="$set('showDeleteAll', false)"
            wire:loading.attr="disabled"
            color="white"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-blade::button.button>
    </x-slot>
</x-dialog-modal>
