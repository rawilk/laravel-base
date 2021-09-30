<button x-data="{ open: false }"
        x-on:set-secondary-nav-open.window="open = $event.detail"
        x-init="$watch('open', value => { $dispatch('set-secondary-nav-open', value) })"
        x-on:click="open = ! open;"
        class="inline-flex items-center justify-center p-2 rounded-md text-blue-gray-400 hover:text-blue-gray-500 hover:bg-blue-gray-100 focus:outline-blue-gray"
        x-bind:aria-expanded="JSON.stringify(open)"
        id="secondary-menu-toggle"
>
    <span class="sr-only">{{ __('Open secondary menu') }}</span>

    {{-- "closed" icon --}}
    <x-heroicon-s-menu id="secondary-menu-closed" class="h-6 w-6" x-bind:class="{ 'hidden': open, 'block': ! open }" />

    {{-- "open" icon --}}
    <x-heroicon-s-x class="h-6 w-6" x-bind:class="{ 'block': open, 'hidden': ! open }" />
</button>
