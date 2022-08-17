<x-slot name="pageTitle">
    <x-layout.page-title>
        {{ __('base::roles.index.title') }}

        @can(\App\Enums\PermissionEnum::ROLES_CREATE->value)
            <x-slot name="actions">
                <x-button href="{!! route('admin.roles.create') !!}" variant="blue">
                    <x-css-math-plus />
                    <span class="capitalize">{{ __('base::messages.add_button', ['item' => __('base::roles.singular')]) }}</span>
                </x-button>
            </x-slot>
        @endcan
    </x-layout.page-title>
</x-slot>
