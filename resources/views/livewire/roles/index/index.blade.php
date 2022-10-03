<div>
    @include('laravel-base::livewire.roles.index.partials.header')

    <div class="py-4 space-y-4">
        {{-- top bar --}}
        @include('laravel-base::livewire.roles.index.partials.topbar')

        {{-- results --}}
        @include('laravel-base::livewire.roles.index.partials.results')

        {{-- delete --}}
        @can(\App\Enums\PermissionEnum::ROLES_DELETE->value)
            @include('laravel-base::livewire.roles.index.partials.confirm-delete')
            @include('laravel-base::livewire.roles.index.partials.confirm-bulk-delete')
        @endcan

        {{-- import --}}
        @canany([\App\Enums\PermissionEnum::ROLES_CREATE->value, \App\Enums\PermissionEnum::ROLES_EDIT->value])
            <livewire:csv-importer
                :model="config('permission.models.role')"
                :import-class="\Rawilk\LaravelBase\Imports\Roles\RolesImport::class"
                :columns-to-map="['name', 'description', 'permissions']"
                :required-columns="['name']"
                :column-labels="['name' => __('base::roles.labels.name'), 'description' => __('base::roles.labels.description'), 'permissions' => __('base::roles.labels.permissions')]"
                :guesses="[
                    'name' => ['name', 'title'],
                    'description' => ['description', 'desc', 'about'],
                    'permissions' => ['permissions', 'perms', 'abilities'],
                ]"
            />
        @endcanany
    </div>
</div>
