<div {{ $attributes->class($itemClasses()) }}>
    <dt {{ $componentSlot($label)->attributes->class($labelClasses()) }}>{{ $label }}</dt>
    <dd @class([
        'mt-1 text-sm leading-5 sm:mt-0 sm:col-span-2',
        'text-slate-500 dark:text-slate-300' => $dimmed,
        'text-gray-900' => ! $dimmed,
    ])>
        {{ $slot }}
    </dd>
</div>
