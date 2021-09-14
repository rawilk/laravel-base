<tr
@if ($role) role="{{ $role }}" @endif
@if ($tabIndex !== false) tabindex="{{ $tabIndex }}" @endif
@if ($rowIndex) aria-rowindex="{{ $rowIndex }}" @endif
@if ($wireLoads) wire:loading.class.delay="opacity-50" @endif
{{ $attributes->class($classes()) }}
>
    {{ $slot }}
</tr>
