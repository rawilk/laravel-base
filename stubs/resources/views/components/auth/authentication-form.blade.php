@props([
    'title' => false,
    'subTitle' => false,
])

<div class="mx-auto w-full lg:max-w-lg">
    <div>
        <x-link :app-link="false" href="/">
            <x-logo class="h-12 w-auto text-blue-600" />
        </x-link>

        @if ($title)
            <h2 class="mt-6 text-3xl leading-9 font-extrabold text-gray-900">{{ $title }}</h2>
        @endif

        @if ($subTitle)
            <p class="mt-2 text-sm leading-5 text-gray-600">{{ $subTitle }}</p>
        @endif
    </div>

    <div class="mt-8">
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
