<x-blade::button.button
    :color="$buttonVariant"
    :size="$size"
    :extra-attributes="$triggerAttributes()"
>
    {{ $triggerText }}

    <x-slot:icon-right>
        <x-heroicon-s-chevron-down />
    </x-slot:icon-right>
</x-blade::button.button>
