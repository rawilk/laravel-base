@props([
    'title' => '',
    'pageTitle' => false,
])

<x-app :title="$title">
    @include('layouts.partials.app-styles-and-scripts')

    <div class="min-h-screen flex bg-gray-50">
        {{-- mobile nav --}}
        @include('layouts.app.partials.mobile-navbar')

        {{-- desktop nav --}}
        @include('layouts.app.partials.desktop-navbar')

        {{-- main content area --}}
        <div class="flex flex-col w-0 flex-1">
            {{-- top "nav" --}}
            @include('layouts.app.partials.header')

            <main class="flex-1 relative focus:outline-none" id="main-content-wrapper" tabindex="0">
                {{-- impersonation notice --}}

                <div class="py-6">
                    {{-- title area --}}
                    @if ($pageTitle)
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            {{ $pageTitle }}
                        </div>
                    @endif

                    <div class="max-w-7xl mx-auto pb-10 lg:px-8">
                        {{-- page content --}}
                        <div class="pb-4 lg:pt-4 space-y-4">
                            @include('layouts.partials.session-alert', ['canDismissAlert' => true])

                            <div>{{ $slot }}</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <x-notification />
    <x-scroll-to-top-button />
</x-app>
