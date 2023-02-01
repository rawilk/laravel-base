<span class="-ml-px relative block">
    <x-blade::button.icon
        :color="$buttonVariant"
        :size="$size"
        class="rounded-md rounded-l-none"
        :extra-attributes="$triggerAttributes()"
    >
        <span class="sr-only">{{ __('base::messages.dropdown.open_menu') }}</span>
        <x-heroicon-s-chevron-down />
    </x-blade::button.icon>
</span>
