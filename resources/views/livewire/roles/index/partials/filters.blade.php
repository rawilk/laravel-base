<x-slot:filters>
    {{-- updated_at min --}}
    <x-form-components::form-group label="{{ __('base::messages.filters.labels.updated_at_min') }}" name="filters.updated-min">
        <x-form-components::inputs.date-picker
            wire:model.defer="filters.updated-min"
            wire:keydown.enter="applyFilters"
            name="filters.updated-min"
            clearable
            focus
        />
    </x-form-components::form-group>

    {{-- updated_at max --}}
    <x-form-components::form-group label="{{ __('base::messages.filters.labels.updated_at_max') }}" name="filters.updated-max">
        <x-form-components::inputs.date-picker
            wire:model.defer="filters.updated-max"
            wire:keydown.enter="applyFilters"
            name="filters.updated-max"
            clearable
        />
    </x-form-components::form-group>

    {{-- created_at min --}}
    <x-form-components::form-group label="{{ __('base::messages.filters.labels.created_at_min') }}" name="filters.created-min">
        <x-form-components::inputs.date-picker
            wire:model.defer="filters.created-min"
            wire:keydown.enter="applyFilters"
            name="filters.created-min"
            clearable
            focus
        />
    </x-form-components::form-group>

    {{-- created_at max --}}
    <x-form-components::form-group label="{{ __('base::messages.filters.labels.created_at_max') }}" name="filters.created-max">
        <x-form-components::inputs.date-picker
            wire:model.defer="filters.created-max"
            wire:keydown.enter="applyFilters"
            name="filters.created-max"
            clearable
        />
    </x-form-components::form-group>
</x-slot:filters>
