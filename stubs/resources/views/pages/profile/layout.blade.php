<x-page :title="$title ?? ''">
    <x-slot name="pageTitle">
        {{-- title here --}}
    </x-slot>

    <x-inner-nav sticky-offset="md:top-20">
        <x-slot name="nav">
            {{-- account info --}}
            <x-inner-nav-item
                href="{!! route('profile.show') !!}"
                icon="css-user"
            >
                {{ __('Account Info') }}
            </x-inner-nav-item>

            {{-- authentication --}}
            <x-inner-nav-item
                href="{!! route('profile.authentication') !!}"
                icon="css-lock"
            >
                {{ __('Authentication') }}
            </x-inner-nav-item>
        </x-slot>

        @yield('slot')
        {{ $slot ?? '' }}
    </x-inner-nav>
</x-page>
