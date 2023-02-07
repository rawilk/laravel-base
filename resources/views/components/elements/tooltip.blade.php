<span
    x-data="tooltip({ placement: '{{ $placement }}', content: '{!! $title !!}' })"
    x-bind:title="title"
    x-bind:aria-describedby="tooltipId"
    x-on:keydown.escape.window="hide"
    {{ $triggerEventListeners() }}
    tabindex="-1"
    {{ $attributes->class('focus:ring-0 focus:outline-slate relative') }}
>
    {{ $slot }}
</span>
