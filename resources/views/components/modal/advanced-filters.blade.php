<div x-data="modal({ id: '{{ $id }}', show: false })"
     x-on:show-filters.window="event => { if (event.detail === '{{ $id }}') { show = true } }"
     x-on:keydown.escape.window="hideModal"
     x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
     x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
     x-show="show"
     class="fixed z-top inset-0 w-screen"
     style="display: none;"
     id="filters--{{ $id }}"
>
    <div class="absolute inset-0 w-screen">
        {{-- backdrop --}}
        <div x-show="show"
             x-on:click="show = false"
             class="absolute inset-0 bg-slate-500 bg-opacity-75"
        >
        </div>

        {{-- panel --}}
        <section
            class="absolute inset-y-0 right-0 pl-10 max-w-full flex sm:pl-16"
        >
            <div x-show="show"
                 role="dialog"
                 aria-modal="true"
                 class="w-screen max-w-xl"
            >
                <div class="h-full flex flex-col space-y-6 bg-slate-800 text-gray-300 dark-inputs shadow-xl">
                    {{-- header --}}
                    <header class="flex-shrink-0">
                        <div class="pt-6 pb-4 sm:pt-10 px-4 sm:px-6">
                            <div class="flex justify-between items-center space-x-2">
                                <div class="text-base sm:text-xl font-semibold">{{ __('base::messages.filters.modal_title') }}</div>
                                <div class="h-7 flex items-center">
                                    <button
                                        x-on:click="show = false"
                                        class="text-slate-400 hover:text-slate-200 hover:bg-slate-400 rounded-full transition-colors focus:ring-2 focus:outline-none focus:ring-offset-1 focus:ring-opacity-25 p-3"
                                    >
                                        <span class="sr-only">{{ __('base::messages.modal.close_button') }}</span>
                                        <x-heroicon-s-x-mark class="h-6 w-6" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </header>

                    {{-- content --}}
                    <section class="min-h-0 flex-1 flex flex-col overflow-y-scroll">
                        <div class="relative flex-1 px-4 sm:px-6">
                            {{ $slot }}
                        </div>
                    </section>

                    {{-- footer --}}
                    @if ($applyClick || $resetClick)
                        <footer class="flex-shrink-0">
                            <div class="pb-4 md:pb-8 pt-2 px-4 sm:px-6 flex flex-col items-center space-y-3">
                                @if ($applyClick)
                                    <x-button
                                        wire:click="{{ $applyClick }}"
                                        wire:target="{{ $applyClick }}"
                                        variant="blue"
                                        block
                                    >
                                        {{ __('base::messages.filters.apply_button') }}
                                    </x-button>
                                @endif

                                @if ($resetClick)
                                    <x-laravel-base::button.link
                                        class="text-xs text-gray-300 hover:text-gray-200"
                                        wire:click="{{ $resetClick }}"
                                        wire:target="{{ $resetClick }}"
                                    >
                                        {{ __('base::messages.filters.reset_button') }}
                                    </x-laravel-base::button.link>
                                @endif
                            </div>
                        </footer>
                    @endif
                </div>
            </div>
        </section>
    </div>

    @if ($closeOnApply)
        <div x-data x-init="$wire.on('filters-applied', () => { show = false })"></div>
    @endif

    @if ($closeOnReset)
        <div x-data x-init="$wire.on('filters-reset', () => { show = false })"></div>
    @endif
</div>
