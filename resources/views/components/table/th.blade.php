@unless ($hidden)
<th tabindex="0"
    @if ($scope) scope="{{ $scope }}" @endif
    @if ($role) role="{{ $role }}" @endif
    @if ($colIndex) aria-colindex="{{ $colIndex }}" @endif
    @if ($sortable)
        {{ $attributes->class($defaultClass)->only('class') }}
    @else
        {{ $attributes->class($defaultClass) }}
    @endif
>
    @unless ($sortable)
        <span class="text-{{ $align }} w-full">{{ $slot }}</span>
    @else
        <button
            {{ $attributes->except('class') }}
            @include('laravel-base::components.table.partials.th-sort-button-attrs')
            @class([
                'flex items-center space-x-1',
                "text-{$align}",
                'text-xs leading-4 font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider group focus:outline-none focus:underline',
                'whitespace-no-wrap' => $nowrap,
            ])
        >
            <span>{{ $slot }}</span>

            <span class="relative flex items-center">
                @include('laravel-base::components.table.partials.th-sort-button-icon')
            </span>
        </button>
    @endif
</th>
@endunless
