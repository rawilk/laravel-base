@props(['breadcrumbs' => []])

<div {{ $attributes->merge(['id' => 'filter-breadcrumbs', 'class' => 'filter-breadcrumbs flex flex-wrap']) }}>
    @foreach ($breadcrumbs as $breadcrumb)
        @if (is_array($breadcrumb['value']))

            @foreach ($breadcrumb['value'] as $value)
                <x-laravel-base::elements.filter-breadcrumb
                    :breadcrumb="[
                        'key' => $breadcrumb['key'],
                        'label' => $breadcrumb['label'],
                        'value' => $value,
                        'valueDisplay' => $breadcrumb['valueDisplay'][$loop->index]
                    ]"
                    wire:key="filter-breadcrumb-{{ $breadcrumb['key'] }}-{{ $value }}"
                />
            @endforeach

        @else
            <x-laravel-base::elements.filter-breadcrumb
                :breadcrumb="$breadcrumb"
                wire:key="filter-breadcrumb-{{ $breadcrumb['key'] }}"
            />
        @endif
    @endforeach
</div>
