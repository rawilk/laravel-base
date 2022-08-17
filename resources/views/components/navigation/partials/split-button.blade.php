<span class="-ml-px relative block">
    <x-laravel-base::button.button
        :variant="$buttonVariant"
        icon
        :size="$size"
        class="rounded-l-none"
        :extra-attributes="$triggerAttributes()"
    >
        <span class="sr-only">{{ __('base::messages.dropdown.open_menu') }}</span>
        <x-heroicon-s-chevron-down aria-hidden="true" />
    </x-laravel-base::button.button>
</span>
