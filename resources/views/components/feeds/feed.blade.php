<div {{ $attributes->class($containerClass()) }}>
    <ul @class([
        'divide-y divide-slate-200' => $stacked,
        '-mb-8' => ! $stacked,
    ])>
        {{ $slot }}
    </ul>
</div>
