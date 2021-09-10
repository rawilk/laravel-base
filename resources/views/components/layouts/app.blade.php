<x-dynamic-component :component="bladeComponentName('html')" :title="$title ?? ''" {{ $attributes }}>
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

    {{ $js ?? '' }}
    @stack('js')
</x-dynamic-component>
