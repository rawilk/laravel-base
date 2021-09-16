<span
    {{ $attributes->class($classes()) }}
    @if ($removeable)
        x-data="{ show: true }"
        x-show="show"
    @endif
>
    @if ($dot)
        <svg @class([
            'badge-dot',
            'mr-1.5 h-2 w-2',
            '-ml-1' => $large,
            '-ml-0.5' => ! $large,
            ])
            fill="currentColor"
            viewBox="0 0 8 8"
        >
            <circle cx="4" cy="4" r="3" />
        </svg>
    @endif

    {{ $slot }}

    @if ($removeable)
        <button
            type="button"
            @class([
                'badge__remove-button',
                'flex-shrink-0 ml-1.5 inline-flex focus:outline-blue-gray',
                '-mr-0.5' => $large,
            ])
            aria-label="{{ __('laravel-base::messages.badge.remove_button') }}"
            x-on:click.prevent.stop="show = false; {{ $onRemoveClick ?? '' }}"
        >
            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
                <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"/>
            </svg>
        </button>
    @endif
</span>
