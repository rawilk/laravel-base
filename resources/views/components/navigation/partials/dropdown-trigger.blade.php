<x-blade::button.button
    :color="$buttonColor"
    :variant="$buttonVariant"
    :size="$size"
    :extra-attributes="$triggerAttributes()"
>
    {{ $triggerText }}

    <x-slot:icon-right>
        <x-heroicon-m-chevron-down />
    </x-slot:icon-right>
</x-blade::button.button>
