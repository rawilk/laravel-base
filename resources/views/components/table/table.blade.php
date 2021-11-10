<div @class([
    'table-container',
    'align-middle min-w-full overflow-hidden overflow-x-auto relative',
    'lg:rounded-lg' => $rounded,
    'shadow' => $shadow,
    $containerClass,
])
>
    <table {{ $attributes->class('table | min-w-full divide-y divide-blue-gray-200') }}>
        @if ($head)
            <thead {{ $head->attributes->merge(['role' => 'rowgroup']) }}>
                {{ $head }}
            </thead>
        @endif

        <tbody
            {{ $componentSlot($tbody)->attributes->merge(['role' => 'rowgroup', 'class' => $tbodyClasses()]) }}
        >
            {{ $slot }}
        </tbody>
    </table>
</div>
