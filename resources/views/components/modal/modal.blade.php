<div x-data="modal({ id: '{{ $id() }}', show: @entangle($attributes->wire('model')) })"
     x-on:close.stop="hideModal"
     x-on:keydown.escape.window="hideModal"
     x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
     x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
     x-show="show"
     class="fixed z-top inset-0 overflow-y-auto"
     style="display: none;"
     id="{{ $id() }}"
>
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Backdrop --}}
        <div x-show="show"
             class="fixed inset-0 transform transition-opacity backdrop-blur"
             x-on:click="hideModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
        >
            <div class="absolute inset-0 bg-slate-500 opacity-50"></div>
        </div>

        {{-- This element is to trick the browser into centering the modal contents --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

        {{-- Modal --}}
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             role="dialog"
             aria-modal="true"
            {{ $attributes->except(['wire:model', 'wire:model.defer'])->merge([
               'class' => "inline-block align-bottom text-left bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full {$maxWidth()}",
            ]) }}
        >
            @if ($showClose)
                <div class="hidden sm:block absolute top-0 right-0 z-10 pt-4 pr-4">
                    <button
                        x-on:click="hideModal"
                        type="button"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-white focus:ring-2 focus:outline-none focus:ring-offset-1 focus:ring-opacity-25 focus:text-gray-500 transition ease-in-out duration-150"
                    >
                        <span class="sr-only">{{ __('base::messages.modal.close_button') }}</span>
                        <x-heroicon-s-x-mark class="h-6 w-6" />
                    </button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>
