<div>
    @include('laravel-base::livewire.roles.index.partials.header')

    <div class="py-4 space-y-4">
        {{-- top bar --}}
        @include('laravel-base::livewire.roles.index.partials.topbar')

        {{-- results --}}
        @include('laravel-base::livewire.roles.index.partials.results')

        {{-- delete --}}
        @can(\App\Support\PermissionName::ROLES_DELETE)
            @include('laravel-base::livewire.roles.index.partials.confirm-delete')
            @include('laravel-base::livewire.roles.index.partials.confirm-bulk-delete')
        @endcan

        {{-- import --}}
        @canany([\App\Support\PermissionName::ROLES_CREATE, \App\Support\PermissionName::ROLES_EDIT])
            <livewire:admin.roles.import />
        @endcanany
    </div>
</div>
