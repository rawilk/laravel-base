<div {{ $attributes->merge(['class' => $alertClass(), 'role' => 'alert']) }}
     @if ($dismiss)
         x-data="{ open: true }"
         x-show="open"
     @endif
>
    <div class="flex">
        @if ($icon)
            <div class="flex-shrink-0">
                <x-dynamic-component :component="$iconComponent()" class="alert-icon | h-5 w-5" />
            </div>
        @endif

        <div @class(['ml-3' => $icon])>
            @if ($title)
                <h3 class="alert-title | text-sm leading-5 font-medium">
                    {{ $title }}
                </h3>
            @endif

            <div class="alert-text">{{ $slot }}</div>
        </div>

        @if ($dismiss)
            <div class="ml-auto pl-3">
                <div class="-mx-1 5 -my-1 5">
                    <button
                        x-on:click="open = false"
                        type="button"
                        class="alert-dismiss | inline-flex items-center p-1.5 rounded-full focus:outline-slate transition-colors"
                    >
                        <x-heroicon-s-x-mark class="h-5 w-5" />

                        <span class="sr-only">{{ __('Dismiss') }}</span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
