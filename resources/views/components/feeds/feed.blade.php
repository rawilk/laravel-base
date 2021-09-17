<div {{ $attributes->class($containerClass()) }}>
    <ul @class([
        'divide-y divide-blue-gray-200' => $stacked,
        '-mb-8' => ! $stacked,
    ])>
        {{ $slot }}
    </ul>
</div>
