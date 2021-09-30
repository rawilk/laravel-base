@php
    // This is kind of useful (also kind of hacky) for certain pages that share a common url with "child"
    // pages, but shouldn't be denoted as "active" when said "child" pages are visited.
    $requiresExactUrlMatch = $requiresExactUrlMatch ?? false;

    $linkActive = $requiresExactUrlMatch
        ? $active && request()->url() === $url
        : $active;
@endphp

<x-link
    :app-link="false"
    href="{{ $url }}"
    x-bind:class="{ 'hidden': ! open }"
    class="{{ $linkActive ? 'active ' . config('site.main_menu.item_active_class') : config('site.main_menu.item_inactive_class') }} {{ config('site.main_menu.submenu_item_class') }}"
    :aria-current="$active && request()->url() === $url ? 'page' : null"
>
    {{ $label }}
</x-link>
