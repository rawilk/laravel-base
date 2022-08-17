<x-laravel-base::navigation.dropdown trigger-text="{{ __('base::messages.table.column_select') }}" :right="$right" {{ $attributes }}>
    @foreach ($columns as $field => $label)
        <x-laravel-base::navigation.dropdown-item
            wire:click.prevent.stop="toggleColumn('{{ $field }}')"
            class="justify-between"
            :active="! $isHidden($field)"
        >
            <span class="truncate text-left w-10/12">{{ $label }}</span>

            <span>
                @unless ($isHidden($field))
                    <x-heroicon-o-check-circle class="colorless text-green-500" />
                @else
                    <x-heroicon-o-x-circle class="colorless text-red-500" />
                @endunless
            </span>
        </x-laravel-base::navigation.dropdown-item>
    @endforeach
</x-laravel-base::navigation.dropdown>
