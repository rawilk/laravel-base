<x-laravel-base::modal.slide-over :id="$id" :wide="$wide" {{ $attributes }}>
    <x-slot name="header">
        <header {{ $componentSlot($header)->attributes->class('px-4 py-6 bg-blue-gray-200 sm:px-6') }}>
            <div class="flex items-start justify-between space-x-3">
                <div class="space-y-1">
                    @if ($title)
                        <div {{ $componentSlot($title)->attributes->class('text-lg leading-7 font-medium text-blue-gray-900') }}>
                            {{ $title }}
                        </div>
                    @endif

                    @if ($subTitle)
                        <div {{ $componentSlot($subTitle)->attributes->class('text-sm text-blue-gray-500 leading-5') }}>
                            {{ $subTitle }}
                        </div>
                    @endif
                </div>

                @if ($showClose)
                    <div class="h-7 flex items-center">
                        <button
                            x-on:click="show = false"
                            class="text-blue-gray-400 hover:text-blue-gray-200 hover:bg-blue-gray-400 rounded-full transition-colors focus:ring-2 focus:outline-none focus:ring-offset-1 focus:ring-opacity-25 p-3"
                        >
                            <span class="sr-only">{{ __('laravel-base::messages.modal.close_button') }}</span>
                            <x-heroicon-s-x class="h-6 w-6" />
                        </button>
                    </div>
                @endif
            </div>
        </header>
    </x-slot>

    {{ $slot }}

    @if ($footer)
        <x-slot name="footer">
            <div {{ $footer->attributes->class('bg-blue-gray-100 px-4 py-4') }}>
                {{ $footer }}
            </div>
        </x-slot>
    @endif
</x-laravel-base::modal.slide-over>
