@unless ($hidden)
<td
    @if ($role) role="{{ $role }}" @endif
    @if ($colIndex !== false) aria-colindex="{{ $colIndex }}" @endif
    {{ $attributes->class('px-6 py-4 whitespace-nowrap text-sm leading-5 dark:text-slate-300') }}
>
    {{ $slot }}
</td>
@endunless
