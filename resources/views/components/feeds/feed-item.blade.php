<li {{ $attributes->class('feed-item') }}>
    <div class="relative pb-8">
        <span class="feed-item-line" aria-hidden="true"></span>

        <div class="relative flex items-start space-x-3">
            <div>
                <div class="relative px-1">
                    <div {{ $componentSlot($icon)->attributes->class('feed-item-ring') }}>
                        {{ $icon }}
                    </div>
                </div>
            </div>

            <div class="min-w-0 flex-1 py-0">

                <div>
                    <div class="text-sm leading-8 text-blue-gray-500">
                        <span class="mr-0 5">{{ $slot }}</span>

                        @if ($ago)
                            <x-laravel-base::elements.tooltip class="ml-1" title="{{ $ago->format($dateFormat) }}">
                                <span class="whitespace-nowrap text-xs">
                                    {{ $ago->shortRelativeDiffForHumans() }}
                                </span>
                            </x-laravel-base::elements.tooltip>
                        @endif
                    </div>

                    @if ($extra)
                        <div {{ $componentSlot($extra)->attributes->class('text-sm leading-8 -mt-2 text-blue-gray-500') }}>
                            {{ $extra }}
                        </div>
                    @endif

                    @if ($ago)
                        <div class="text-xs text-blue-gray-500">
                            {{ $ago->format($dateFormat) }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</li>
