<div {{ $attributes->class('card bg-white shadow lg:rounded-lg dark:bg-gray-800') }}>
    @if ($header)
        <div {{ $componentSlot($header)->attributes->class('card-header px-4 py-5 sm:px-6 lg:rounded-t-lg ' . $headerColorClasses()) }}>
            {{ $header }}
        </div>
    @endif

    <div @class([
        'card-body',
        'px-4 py-5 sm:p-6' => ! $flush,
    ])>
        {{ $slot }}
    </div>

    @if ($footer)
        <div {{ $componentSlot($footer)->attributes->class('card-footer bg-slate-50 dark:bg-gray-700 px-4 py-4 sm:px-6 lg:rounded-b-lg') }}>
            {{ $footer }}
        </div>
    @endif
</div>
