<button x-data="{ open: false }"
        x-on:set-secondary-nav-open.window="open = $event.detail"
        x-init="$watch('open', value => { $dispatch('set-secondary-nav-open', value) })"
        x-on:click="open = ! open;"
        class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-slate"
        x-bind:aria-expanded="JSON.stringify(open)"
        id="secondary-menu-toggle"
>
    <span class="sr-only">{{ __('Open secondary menu') }}</span>

    {{-- "closed" icon --}}
    <x-heroicon-s-menu id="secondary-menu-closed" class="h-6 w-6" x-bind:class="{ 'hidden': open, 'block': ! open }" />

    {{-- "open" icon --}}
    <x-heroicon-s-x class="h-6 w-6" x-bind:class="{ 'block': open, 'hidden': ! open }" />
</button>
