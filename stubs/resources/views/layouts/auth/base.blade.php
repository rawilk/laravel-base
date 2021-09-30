<x-app :title="$title ?? ''">
    @include('layouts.partials.app-styles-and-scripts')

    <div class="min-h-screen bg-white flex">
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-15 lg:flex-none lg:w-1/2 xl:w-1/3">
            {{ $slot }}
        </div>

        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover"
                 src="https://images.unsplash.com/photo-1505904267569-f02eaeb45a4c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1908&q=80"
                 alt="login cover image"
            >
        </div>
    </div>
</x-app>
