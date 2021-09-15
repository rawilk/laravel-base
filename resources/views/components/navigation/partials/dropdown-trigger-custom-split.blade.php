<div class="relative z-0 flex">
    <div class="flex-1 max-w-full" style="width: calc(100% - 3rem);"
         {{ $componentSlot($trigger)->attributes }}
    >
        {{ $trigger ?? '' }}
    </div>

    @include('laravel-base::components.navigation.partials.split-button')
</div>
