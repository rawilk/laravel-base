<!DOCTYPE html>
<html {{ $componentSlot($html)->attributes->merge(['lang' => str_replace('_', '-', app()->getLocale())]) }}>
    <head>
        {{ $headTop ?? '' }}
        @include('laravel-base::components.layouts.partials.meta')
        <title>{{ $title() }}</title>

        {{ $head ?? '' }}
    </head>
    <body {{ $attributes }}>
        {{ $slot }}
    </body>
</html>
