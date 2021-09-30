<button x-data="{ open: false }"
        x-on:set-nav-open.window="open = $event.detail"
        x-init="$watch('open', value => { $dispatch('set-nav-open', value) })"
        x-on:click="open = ! open"
        class="h-full px-4 border-r border-blue-gray-200 text-blue-gray-500 focus:outline-blue-gray focus:bg-blue-gray-100 focus:text-blue-gray-600 lg:hidden"
        aria-label="{{ __('Open sidebar') }}"
        x-bind:aria-expanded="JSON.stringify(open)"
>
    <x-css-menu-right class="w-6 h-6" />
</button>
