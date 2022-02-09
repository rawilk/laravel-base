<div {{ $attributes->class('action-item | group') }}
     x-data
     x-on:click="$refs.link.focus(); $refs.link.click();"
>
    @if ($before)
        <div {{ $before->attributes }}>{{ $before }}</div>
    @endif

    @if ($icon)
        <div class="rounded-lg inline-flex p-3 {{ $iconClass }}">
            <x-dynamic-component :component="$icon" class="h-6 w-6" />
        </div>
    @endif

    <div @class(['mt-8' => $icon || $before])>
        <h3 class="text-lg font-medium">
            <x-laravel-base::navigation.link
                :app-link="false"
                href="{{ $href }}"
                class="focus:outline-none"
                hide-external-indicator
                x-ref="link"
            >
                {{-- extended touch target to entire panel --}}
                <span class="absolute inset-0" aria-hidden="true"></span>
                {{ $slot }}
            </x-laravel-base::navigation.link>
        </h3>

        @if ($description)
            <p {{ $componentSlot($description)->attributes->class('mt-2 text-sm text-slate-500') }}>
                {{ $description }}
            </p>
        @endif

        @if ($extra)
            <div {{ $extra->attributes }}>{{ $extra }}</div>
        @endif
    </div>

    {{-- arrow up right --}}
    <span class="pointer-events-none absolute top-6 right-6 text-slate-300 group-hover:text-slate-400" aria-hidden="true">
        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z" />
        </svg>
    </span>
</div>
