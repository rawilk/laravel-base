<div>
    <button
        x-on:click="open = ! open"
        x-bind:class="{
            '{{ config('site.main_menu.item_active_class') }} mb-1': hasActiveChild || open,
            '{{ config('site.main_menu.item_inactive_class') }}': ! hasActiveChild && ! open,
        }"
        class="{{ config('site.main_menu.item_base_class') }} w-full"
        aria-haspopup="true"
    >
        @isset ($icon)
            <x-dynamic-component
                :component="$icon"
                class="{{ config('site.main_menu.icon_base_class') }}"
                x-bind:class="{
                    '{{ config('site.main_menu.icon_active_class') }}': hasActiveChild || open,
                    '{{ config('site.main_menu.icon_inactive_class') }}': ! hasActiveChild && ! open,
                }"
            />
        @endisset

        <span>{{ $label }}</span>

        <x-css-chevron-right
            class="ml-auto h-5 w-5 transform group-hover:text-slate-400 group-focus:text-slate-400"
            x-bind:class="{ 'rotate-90': open, 'text-slate-300': ! open && ! hasActiveChild, 'text-slate-400': hasActiveChild || open }"
        />
    </button>
</div>
