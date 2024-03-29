<x-laravel-base::modal.slide-over :id="$id" :wide="$wide" {{ $attributes }}>
    <x-slot:header>
        <header {{ $componentSlot($header)->attributes->class('px-4 py-6 bg-slate-200 dark:bg-gray-800 sm:px-6 sticky top-0 z-10') }}>
            <div class="flex items-start justify-between space-x-3">
                <div class="space-y-1">
                    @if ($title)
                        <div {{ $componentSlot($title)->attributes->class('text-lg leading-7 font-medium text-slate-900 dark:text-white') }}>
                            {{ $title }}
                        </div>
                    @endif

                    @if ($subTitle)
                        <div {{ $componentSlot($subTitle)->attributes->class('text-sm text-slate-500 dark:text-slate-300 leading-5') }}>
                            {{ $subTitle }}
                        </div>
                    @endif
                </div>

                @if ($showClose)
                    <div class="h-7 flex items-center">
                        <button
                            x-on:click="show = false"
                            class="text-slate-400 hover:text-slate-200 hover:bg-slate-400 rounded-full transition-colors focus:ring-2 focus:outline-none focus:ring-offset-1 focus:ring-opacity-25 p-3"
                        >
                            <span class="sr-only">{{ __('base::messages.modal.close_button') }}</span>
                            <x-heroicon-s-x-mark class="h-6 w-6" />
                        </button>
                    </div>
                @endif
            </div>
        </header>
    </x-slot:header>

    {{ $slot }}

    @if ($footer)
        <x-slot:footer>
            @isset($footerBefore)
                {{ $footerBefore }}
            @endisset

            <div {{ $footer->attributes->class('bg-slate-100 dark:bg-gray-800 px-4 py-4') }}>
                {{ $footer }}
            </div>
        </x-slot:footer>
    @endif
</x-laravel-base::modal.slide-over>
