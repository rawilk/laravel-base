<x-table>
    <x-slot:head>
        <x-tr>
            <x-th class="table-check">
                <x-checkbox wire:model="selectPage" />
            </x-th>
            <x-th class="table-actions" />
            <x-th sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null" :hidden="$this->isHidden('id')">{{ __('base::messages.labels.model.id') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" :hidden="$this->isHidden('name')" class="w-full">{{ __('base::roles.labels.name') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('description')" :direction="$sorts['description'] ?? null" :hidden="$this->isHidden('description')">{{ __('base::roles.labels.description') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null" :hidden="$this->isHidden('created_at')">{{ __('base::messages.labels.model.created_at') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('updated_at')" :direction="$sorts['updated_at'] ?? null" :hidden="$this->isHidden('updated_at')">{{ __('base::messages.labels.model.updated_at') }}</x-th>
        </x-tr>
    </x-slot:head>

    <x-laravel-base::table.selected-message
        :select-page="$selectPage"
        :select-all="$selectAll"
        :count="$this->selectableRowCount"
        :total="$roles->total() - count(\Rawilk\LaravelBase\Models\Role::protectedRoleNames())"
        :colspan="$this->visibleColumns"
        item-name="roles"
    />

    @forelse ($roles as $role)
        <x-tr row-index="{{ $role->id }}"
              wire-key="row-{{ $role->id }}"
              :selected="$this->isSelected($role->id)"
              wire-loads
        >
            <x-td class="table-check">
                @unless ($role->isProtected())
                    <x-checkbox wire:model="selected" value="{{ $role->id }}" />
                @endunless
            </x-td>

            <x-td class="table-actions">
                <x-action-menu :right="false">
                    @can('edit', $role)
                        <x-dropdown-item href="{{ $role->edit_url }}">
                            <x-heroicon-s-pencil />
                            <span>{{ __('base::messages.edit_button') }}</span>
                        </x-dropdown-item>
                    @endcan

                    @can('delete', $role)
                        <x-dropdown-item wire:click="confirmDelete('{{ $role->id }}')">
                            <x-css-trash />
                            <span>{{ __('base::messages.delete_button') }}</span>
                        </x-dropdown-item>
                    @endcan
                </x-action-menu>
            </x-td>

            <x-td :hidden="$this->isHidden('id')">{{ $role->id }}</x-td>
            <x-td :hidden="$this->isHidden('name')">
                @can('edit', $role)
                    <x-link href="{{ $role->edit_url }}">
                        {!! $this->highlight(e($role->name)) !!}
                    </x-link>
                @else
                    <span>{!! $this->highlight(e($role->name)) !!}</span>
                @endcan
            </x-td>
            <x-td :hidden="$this->isHidden('description')">
                <span class="block truncate">{!! $this->highlight(e($role->description)) !!}</span>
            </x-td>
            <x-td :hidden="$this->isHidden('created_at')">{{ $role->created_at_for_humans }}</x-td>
            <x-td :hidden="$this->isHidden('updated_at')">{{ $role->updated_at_for_humans }}</x-td>
        </x-tr>
    @empty
        <x-laravel-base::table.no-results colspan="{{ $this->visibleColumns }}">
            {{ __('base::roles.alerts.no_results') }}
        </x-laravel-base::table.no-results>
    @endforelse
</x-table>

<div class="content-container">{{ $roles->links() }}</div>
