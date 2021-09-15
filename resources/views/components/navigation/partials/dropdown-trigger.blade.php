<x-laravel-base::button.button
    :variant="$buttonVariant"
    :size="$size"
    :extra-attributes="$triggerAttributes()"
>
    <span>{{ $triggerText }}</span>
    <x-heroicon-s-chevron-down aria-hidden="true" />
</x-laravel-base::button.button>
