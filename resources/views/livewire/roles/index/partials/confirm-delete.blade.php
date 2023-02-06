<x-dialog-modal wire:model.defer="showDelete" max-width="md">
    <x-slot:title>
        {{ __('base::roles.confirm_delete.title') }}
    </x-slot:title>

    <x-slot:content>
        <p>{!! __('base::roles.confirm_delete.text', ['role' => $deleting?->name]) !!}</p>
    </x-slot:content>

    <x-slot:footer>
        <x-blade::button.button
            wire:click="deleteRole"
            color="red"
        >
            {{ __('base::messages.delete_button') }}
        </x-blade::button.button>

        <x-blade::button.button
            wire:click="$set('showDelete', false)"
            color="slate"
            variant="text"
        >
            {{ __('base::messages.confirm_modal_cancel') }}
        </x-blade::button.button>
    </x-slot:footer>
</x-dialog-modal>
