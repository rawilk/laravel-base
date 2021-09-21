<x-laravel-base::navigation.link
    :app-link="false"
    :href="$href"
    hide-external-indicator
    {{ $attributes->class($linkClass()) }}
>
    @if ($icon)
        <x-dynamic-component
            :component="$icon"
            aria-hidden="true"
            :class="$iconClass()"
        />
    @endif

    <span class="truncate w-full">{{ $slot }}</span>
</x-laravel-base::navigation.link>
