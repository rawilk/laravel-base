<div x-data="modal({ id: '{{ $id }}', show: @entangle($attributes->wire('model')) })"
     x-on:close.stop="hideModal"
     x-on:keydown.escape.window="hideModal"
     x-on:keydown.tab.prevent="$event.shiftKey || nextFocusable().focus()"
     x-on:keydown.shift.tab.prevent="prevFocusable().focus()"
     x-show="show"
     class="fixed z-top inset-0 w-screen"
     style="display: none;"
     id="{{ $id }}"
>
    <div class="absolute inset-0 w-screen">
        {{-- Backdrop --}}
        <div x-show="show"
             x-on:click="show = false"
             x-transition:enter="ease-in-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-slate-500 bg-opacity-50 backdrop-blur transition-opacity"
        >
        </div>

        <section @class([
            'absolute inset-y-0 right-0 pl-10 max-w-full flex',
            'sm:pl-16' => $wide,
        ])>
            {{-- Panel --}}
            <div x-show="show"
                 x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 role="dialog"
                 aria-modal="true"
                {{ $attributes->except(['wire:model', 'wire:model.defer'])->merge([
                   'class' => 'w-screen ' . ($wide ? 'max-w-2xl' : 'max-w-md')
                ]) }}
            >
                {{-- Content --}}
                <div class="h-full flex flex-col bg-white shadow-xl">
                    <div class="min-h-0 flex-1 flex flex-col space-y-6 overflow-y-scroll">
                        {{-- Header --}}
                        @if ($header)
                            {{ $header }}
                        @endif

                        {{-- Body --}}
                        <div class="relative flex-1 px-4 sm:px-6 pb-4">
                            {{ $slot }}
                        </div>
                    </div>

                    {{-- Footer --}}
                    @if ($footer)
                        <div {{ $footer->attributes->class('flex-shrink-0') }}>
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
