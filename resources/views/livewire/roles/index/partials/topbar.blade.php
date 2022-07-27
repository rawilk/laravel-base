<div>
    <x-topbar
        :hideable-columns="$hideableColumns"
        :hidden-columns="$hidden"
        search-placeholder="{{ __('laravel-base::roles.labels.search_placeholder') }}"
    >
        <div class="topbar-section" id="bulk-actions">
            <x-dropdown trigger-text="{{ __('laravel-base::messages.bulk_actions') }}" right>
                {{-- import --}}
                @canany([\App\Enums\PermissionEnum::ROLES_CREATE->value, \App\Enums\PermissionEnum::ROLES_EDIT->value])
                    <x-dropdown-item wire:click="$emit('{{ \Rawilk\LaravelBase\Http\Livewire\Roles\Import::$showEvent }}', '{{ \Rawilk\LaravelBase\Http\Livewire\Roles\Import::importId() }}')">
                        <x-css-software-upload />
                        <span>{{ __('laravel-base::messages.import_button') }}</span>
                    </x-dropdown-item>
                @endcanany

                {{-- export --}}
                <x-dropdown-item wire:click="exportSelected">
                    <x-css-software-download />
                    <span>{{ __('laravel-base::messages.export_button') }}</span>
                </x-dropdown-item>

                {{-- delete --}}
                @can(\App\Enums\PermissionEnum::ROLES_DELETE->value)
                    <x-dropdown-divider />
                    <x-dropdown-item wire:click="$toggle('showDeleteAll')">
                        <x-css-trash />
                        <span>{{ __('laravel-base::messages.delete_button') }}</span>
                    </x-dropdown-item>
                @endcan
            </x-dropdown>
        </div>

        @include('laravel-base::livewire.roles.index.partials.filters')
    </x-topbar>

    {{-- filter breadcrumbs --}}
    <div @class([
        'content-container',
        'mt-4' => $this->filterBreadcrumbs->isNotEmpty(),
    ])>
        <x-laravel-base::elements.filter-breadcrumbs :breadcrumbs="$this->filterBreadcrumbs" />
    </div>
</div>
