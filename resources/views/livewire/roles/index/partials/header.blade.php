<x-slot name="pageTitle">
    <x-layout.page-title>
        {{ __('laravel-base::roles.index.title') }}

        @can(\App\Support\PermissionName::ROLES_CREATE)
            <x-slot name="actions">
                <x-button href="{!! route('admin.roles.create') !!}" variant="blue">
                    <x-css-math-plus />
                    <span class="capitalize">{{ __('laravel-base::messages.add_button', ['item' => __('laravel-base::roles.singular')]) }}</span>
                </x-button>
            </x-slot>
        @endcan
    </x-layout.page-title>
</x-slot>
