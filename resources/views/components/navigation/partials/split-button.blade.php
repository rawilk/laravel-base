<span class="dropdown-split-button -ml-px relative block">
    <x-blade::button.icon
        :color="$buttonColor"
        :variant="$buttonVariant"
        :size="$size"
        class="rounded-md rounded-l-none"
        :extra-attributes="$triggerAttributes()"
    >
        <span class="sr-only">{{ __('base::messages.dropdown.open_menu') }}</span>
        <x-heroicon-m-chevron-down />
    </x-blade::button.icon>
</span>
