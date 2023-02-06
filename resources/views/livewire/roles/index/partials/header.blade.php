<x-slot:page-title>
    <x-layout.page-title>
        {{ __('base::roles.index.title') }}

        @can(\App\Enums\PermissionEnum::ROLES_CREATE->value)
            <x-slot:actions>
                <x-blade::button.button href="{!! route('admin.roles.create') !!}" color="blue" left-icon="css-math-plus" x-data="">
                    <span class="capitalize">{{ __('base::messages.add_button', ['item' => __('base::roles.singular')]) }}</span>
                </x-blade::button.button>
            </x-slot:actions>
        @endcan
    </x-layout.page-title>
</x-slot:page-title>
