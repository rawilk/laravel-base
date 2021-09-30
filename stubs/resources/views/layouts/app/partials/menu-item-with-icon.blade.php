<x-link
    :app-link="false"
    href="{{ $url }}"
    class="{{ config('site.main_menu.item_base_class') }} {{ config($active ? 'site.main_menu.item_active_class' : 'site.main_menu.item_inactive_class') }}"
    :aria-current="$active && request()->url() === $url ? 'page' : null"
>
    <x-dynamic-component
        :component="$icon"
        class="{{ config('site.main_menu.icon_base_class') }} {{ config($active ? 'site.main_menu.icon_active_class' : 'site.main_menu.icon_inactive_class') }}"
    />

    <span>{{ $label }}</span>
</x-link>
