<div x-data="{ open: false }"
     x-on:set-nav-open.window="open = $event.detail"
     x-on:keydown.escape.window="open = false; $dispatch('set-nav-open', false)"
     class="lg:hidden"
>
    <div x-show="open"
         class="fixed inset-0 flex z-top"
    >
        {{-- backdrop --}}
        <div x-show="open"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0"
             aria-hidden="true"
        >
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>

        {{-- menu --}}
        <div x-show="open"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             x-on:click.outside="$dispatch('set-nav-open', false)"
             class="relative flex-1 flex flex-col max-w-xs w-full"
        >
            <div class="absolute top-0 right-0 -mr-14 p-1 z-[9001]">
                <button
                    x-data="{ open: false }"
                    x-on:set-nav-open.window="open = $event.detail"
                    x-init="$watch('open', value => { $dispatch('set-nav-open', value) })"
                    x-on:click="open = false"
                    class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-blue-gray focus:bg-gray-600"
                    aria-label="{{ __('Close sidebar') }}"
                >
                    <x-css-close class="h-6 w-6 text-white" />
                </button>
            </div>

            @include('layouts.app.partials.navigation')
        </div>

        <div class="flex-shrink-0 w-14">
            {{-- Dummy element to force sidebar to shrink to fit close icon --}}
        </div>
    </div>
</div>
