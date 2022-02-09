<nav class="bg-white shadow sticky top-0 z-[9001]">
    <div class="mx-auto pr-2 sm:pr-4 lg:pr-8">
        <div class="flex justify-between h-16">
            {{-- main nav mobile toggle --}}
            <div class="flex pr-2 lg:pr-0">
                <div class="flex-shrink-0 flex items-center">
                    @include('layouts.app.partials.mobile-menu-toggle')
                </div>
            </div>

            {{-- mobile menu toggle --}}
            <div class="flex items-center lg:hidden">
                @include('layouts.app.partials.header-menu-toggle')
            </div>

            <div class="hidden lg:flex lg:items-center">
                {{-- user profile dropdown --}}
                <div class="ml-4 relative flex-shrink-0">
                    <livewire:profile-navigation-menu view="layouts.app.partials.profile-navigation-menu" />
                </div>
            </div>
        </div>
    </div>

    {{-- mobile secondary menu --}}
    <div x-data="{
            open: false,
            onClickOutside: function (event) {
                if (event.target.id === 'secondary-menu-closed' || event.target.id === 'secondary-menu-toggle') {
                    return;
                }

                this.open = false;
            },
        }"
         x-on:set-secondary-nav-open.window="open = $event.detail"
         x-on:keydown.escape.window="open = false"
         x-on:click.outside="onClickOutside"
         x-init="$watch('open', value => { $dispatch('set-secondary-nav-open', value) })"
         class="lg:hidden"
         x-bind:class="{ 'block': open, 'hidden': ! open }"
    >
        <div class="pt-4 pb-3 border-t border-gray-200">
            <livewire:profile-navigation-menu view="layouts.app.partials.mobile-profile-navigation" />

            {{-- user menu --}}
            <div class="mt-3 space-y-1">
                {{-- profile link here --}}
                <x-link
                    :app-link="false"
                    href="{!! route('profile.show') !!}"
                    class="block px-4 py-2 text-base font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 focus:outline-slate"
                >
                    {{ __('My profile') }}
                </x-link>

                <button
                    type="submit"
                    form="logout-form"
                    class="block w-full text-left px-4 py-2 text-base font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 focus:outline-slate"
                >
                    {{ __('labels.user.logout_button') }}
                </button>
            </div>
        </div>
    </div>
</nav>

@include('layouts.partials.logout-form')
