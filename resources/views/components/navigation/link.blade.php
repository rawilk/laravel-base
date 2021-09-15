<a href="{{ $href }}"
   @if ($isExternalLink())
       rel="{{ $rel($attributes->get('rel')) }}"
   @endif
   {{ $attributes->class($classes()) }}
>
   {{ $slot }}
</a>
