<div x-data="tabs({ id: '{{ $id }}' })"
     x-cloak
     x-on:register-tab.stop="registerTab($event.detail)" {{-- we want to stop event propagation once we get to here --}}
     wire:ignore.self
     data-tabs
     {{ $attributes }}
>
    {{-- select tabs element for smaller screens --}}
    @include('laravel-base::components.tabs.partials.tab-select')

    {{-- main tab nav for larger screens --}}
    <div class="tab-nav | hidden sm:block"
         x-ref="nav"
         x-on:keydown.arrow-right.prevent="onArrowRight"
         x-on:keydown.arrow-left.prevent="onArrowLeft"
         x-on:keydown.home.prevent="onHome"
         x-on:keydown.end.prevent="onEnd"
    >
        @includeIf($navView())
    </div>

    {{-- tabs content --}}
    <div x-ref="tabs"
         class="tab-content"
    >
        {{ $slot }}
    </div>
</div>
