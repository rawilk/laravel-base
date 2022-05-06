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
            <livewire:admin.roles.import />
        @endcanany
    </div>
</div>
