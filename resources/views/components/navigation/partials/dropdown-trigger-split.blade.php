<span class="relative z-0 inline-flex">
    <x-blade::button.button
        :color="$buttonVariant"
        :size="$size"
        class="rounded-r-none"
        :extra-attributes="$componentSlot($triggerText)->attributes"
    >
        {{ $triggerText }}
    </x-blade::button.button>

    @include('laravel-base::components.navigation.partials.split-button')
</span>
