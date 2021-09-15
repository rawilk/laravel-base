@php($wireTarget = $attributes->wire('target')->value())

<span class="relative inline-flex {{ $containerClass }}">
    @include('laravel-base::components.button.partials.button')
</span>
