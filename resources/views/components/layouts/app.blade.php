<x-laravel-base::layouts.html :title="$title ?? ''" {{ $attributes }}>
    <x-slot:head-top>
        {{ $headTop ?? '' }}
        @stack('head-top')
    </x-slot:head-top>

    <x-slot:head>
        @if ($livewire)
            @livewireStyles(['nonce' => cspNonce()])
        @endif

        {{ $head ?? '' }}
        @stack('head')
    </x-slot:head>

    {{ $slot }}

    @if ($livewire)
        @livewireScripts(['nonce' => cspNonce()])
    @endif

    @if ($laravelFormComponents)
        <fc:scripts />
    @endif

    @if ($bladeAssets)
        <blade:scripts />
    @endif

    @if ($assets)
        <lb:scripts />
    @endif

    {{ $js ?? '' }}
    @stack('js')
</x-laravel-base::layouts.html>
