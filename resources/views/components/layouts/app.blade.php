<x-laravel-base::layouts.html :title="$title ?? ''" {{ $attributes }}>
    <x-slot name="headTop">
        {{ $headTop ?? '' }}
        @stack('head-top')
    </x-slot>

    <x-slot name="head">
        @if ($livewire)
            <livewire:styles />
        @endif

        {{ $head ?? '' }}
        @stack('head')
    </x-slot>

    {{ $slot }}

    @if ($livewire)
        <livewire:scripts />
    @endif

    @if ($laravelFormComponents)
        @fcJavaScript
    @endif

    @if ($assets)
        @lbJavaScript
    @endif

    {{ $js ?? '' }}
    @stack('js')
</x-laravel-base::layouts.html>
