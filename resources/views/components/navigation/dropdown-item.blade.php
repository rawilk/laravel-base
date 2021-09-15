@if ($href)
    <a role="menuitem"
       href="{{ $href }}"
       tabindex="-1"
       @if ($isExternalLink())
           rel="{{ $rel($attributes->get('rel')) }}"
       @endif
       {{ $attributes->class($classes()) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="button"
        role="menuitem"
        tabindex="-1"
        {{ $attributes->class($classes()) }}
    >
        {{ $slot }}
    </button>
@endif
