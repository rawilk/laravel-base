<div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
    <aside @class([
        'lg:col-span-3 lg:px-0 lg:py-0 py-6',
        'px-4' => $stickyNav,
        'px-2 sm:px-6' => ! $stickyNav,
    ])>
        <nav {{ $componentSlot($nav)->attributes->class($navClass()) }}>
            {{ $nav }}
        </nav>
    </aside>

    <div {{ $attributes->class($contentClass()) }}>
        {{ $slot }}
    </div>
</div>
