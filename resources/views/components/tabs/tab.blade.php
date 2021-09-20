<div x-data="tab({{ $optionsToJson() }})"
     x-cloak
     x-show="show"
     x-bind:aria-labelledby="`tab-${id}`"
     x-bind:id="`tab-panel-${id}`"
     role="tabpanel"
     tabindex="0"
     {{ $attributes->class('focus:outline-none pt-6') }}
>
    {{ $slot }}
</div>
