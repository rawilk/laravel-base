<span
    x-data="tooltip({ placement: '{{ $placement }}', content: '{!! $title !!}' })"
    x-bind:title="title"
    x-bind:aria-describedby="tooltipId"
    x-on:keydown.escape.window="hide"
    {{ $triggerEventListeners() }}
    tabindex="0"
    {{ $attributes->class('focus:ring-0 focus:outline-blue-gray relative') }}
>
    {{ $slot }}
</span>
