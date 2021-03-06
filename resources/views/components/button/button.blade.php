@php($wireTarget = $attributes->wire('target')->value())

<span @class([
    'button-container',
    'relative inline-flex',
    'w-full button--block' => $block,
    $containerClass,
])>
    @include('laravel-base::components.button.partials.button')
</span>
