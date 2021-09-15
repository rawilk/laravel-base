<span class="relative z-0 inline-flex">
    <x-laravel-base::button.button
        :variant="$buttonVariant"
        :size="$size"
        class="rounded-r-none"
        :extra-attributes="$componentSlot($triggerText)->attributes"
    >
        <span>{{ $triggerText }}</span>
    </x-laravel-base::button.button>

    @include('laravel-base::components.navigation.partials.split-button')
</span>
