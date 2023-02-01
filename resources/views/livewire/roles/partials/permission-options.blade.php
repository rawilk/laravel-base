@php
    $roleName = $roleName ?? null;
    $canEdit = $roleName !== \Rawilk\LaravelBase\Models\Role::$adminName;
@endphp

@unless ($canEdit)
    <x-alert :type="\Rawilk\LaravelBase\Components\Alerts\Alert::WARNING" class="mb-4">
        <p>{!! __('base::roles.alerts.permissions_cannot_be_modified', ['name' => $roleName]) !!}</p>
    </x-alert>
@endunless

<div x-data="{
        permissions: @entangle('state.permissions').defer,
        has(id) {
            return this.permissions.includes(id);
        },
        selectAllIn(el) {
            el && el.querySelectorAll('[type=checkbox]').forEach(el => {
                if (! el.checked) {
                    this.permissions.push(el.value);
                }
            });
        },
        removeAllIn(el) {
            el && el.querySelectorAll('[type=checkbox]').forEach(el => {
                if (el.checked) {
                    this.permissions.splice(
                        this.permissions.indexOf(el.value), 1
                    );
                }
            });
        },
     }"
     class="space-y-4"
>
    <div class="sm:flex sm:justify-between sm:space-x-2">
        <div class="text-xs">
            {{-- select all --}}
            @if ($canEdit)
                <span>{{ __('base::messages.labels.form.make_selection') }}</span>

                <x-blade::button.link x-on:click="selectAllIn($root)">
                    {{ __('base::messages.labels.form.select_all') }}
                </x-blade::button.link>
                <span>/</span>
                <x-blade::button.link x-on:click="removeAllIn($root)">
                    {{ __('base::messages.labels.form.select_none') }}
                </x-blade::button.link>
            @endif
        </div>

        {{-- collapse/expand all --}}
        <div class="text-xs mt-2 sm:mt-0">
            <x-blade::button.link x-on:click="$dispatch('perm-collapse')">
                {{ __('base::messages.labels.form.collapse_all') }}
            </x-blade::button.link>
            <span>/</span>
            <x-blade::button.link x-on:click="$dispatch('perm-expand')">
                {{ __('base::messages.labels.form.expand_all') }}
            </x-blade::button.link>
        </div>
    </div>

    @foreach ($permissions as $groupName => $groupedPermissions)
        <div wire:key="perm-group-{{ $loop->index }}"
             x-data="{
                open: true,
                groupPermissionIds: {{ $groupedPermissions->pluck('id')->toJson() }},
                allSelected() {
                    return this.groupPermissionIds.every(id => this.has(String(id)));
                },
                someSelected() {
                    return this.groupPermissionIds.some(id => this.has(String(id)));
                },
                status() {
                    if (this.allSelected()) {
                        return 'all';
                    }

                    if (this.someSelected()) {
                        return 'some';
                    }

                    return 'none';
                },
             }"
             x-on:perm-collapse.window="open = false"
             x-on:perm-expand.window="open = true"
             id="perm-group-{{ $loop->index }}"
             x-cloak
        >
            <div class="bg-slate-50 rounded-lg">
                <div class="px-4 pb-5 sm:pb-6 sm:px-6"
                     x-bind:class="{ 'pb-5 sm:pb-6': open, 'pb-3': ! open }"
                >
                    <div x-on:click="open = ! open"
                         class="flex items-center justify-between space-x-1 cursor-pointer group pt-5 sm:pt-6"
                         role="button"
                    >
                        <h4 class="capitalize text-sm leading-6 text-slate-900 font-medium group-hover:text-slate-600 transition-colors flex items-center space-x-1">
                            <span>{{ $groupName }}</span>

                            <x-heroicon-s-check-circle
                                class="w-5 h-5 text-green-500"
                                x-show="status() === 'all'"
                            />

                            <x-heroicon-o-minus-circle
                                class="w-5 h-5 text-yellow-500"
                                x-show="status() === 'some'"
                            />
                        </h4>

                        <div class="p-1 h-6 w-6 rounded-full group-hover:bg-slate-400 transition-all flex justify-center items-center">
                            <x-css-chevron-down
                                class="h-5 w-5 text-slate-600 group-hover:text-slate-200 transition-all"
                                x-bind:class="{ 'rotate-[270deg]': ! open }"
                            />
                        </div>
                    </div>

                    <div class="pt-3" x-show="open" x-collapse>
                        {{-- select all in group --}}
                        @if ($canEdit)
                            <div class="text-xs">
                                <span>{{ __('base::messages.labels.form.make_selection') }}</span>

                                <x-blade::button.link x-on:click="selectAllIn($root)">
                                    {{ __('base::messages.labels.form.select_all') }}
                                </x-blade::button.link>
                                <span>/</span>
                                <x-blade::button.link x-on:click="removeAllIn($root)">
                                    {{ __('base::messages.labels.form.select_none') }}
                                </x-blade::button.link>
                            </div>
                        @endif

                        <div class="mt-2 -space-y-px bg-white rounded-md">
                            @foreach ($groupedPermissions as $permission)
                                <div wire:key="perm-{{ $permission->id }}"
                                     @class([
                                        'relative border first:rounded-tl-md first:rounded-tr-md last:rounded-bl-md last:rounded-br-md',
                                        'opacity-75' => ! $canEdit,
                                     ])
                                     x-bind:class="{ 'bg-blue-50 border-blue-200 z-10': has('{{ $permission->id }}'), 'border-slate-200': ! has('{{ $permission->id }}') }"
                                >
                                    <label @class([
                                        'flex p-4',
                                        'cursor-pointer' => $canEdit,
                                        'cursor-not-allowed' => ! $canEdit,
                                    ])>
                                        <div class="flex items-center h-5">
                                            <x-form-components::choice.checkbox
                                                x-model="permissions"
                                                value="{{ $permission->id }}"
                                                id="perm-option-{{ $permission->id }}"
                                                name="permissions[]"
                                                :disabled="! $canEdit"
                                            />
                                        </div>

                                        <div class="ml-3 flex flex-col">
                                            <span class="block text-sm font-medium"
                                                  x-bind:class="{ 'text-blue-900': has('{{ $permission->id }}'), 'text-slate-900': ! has('{{ $permission->id }}') }"
                                            >
                                                {{ $permission->name }}
                                            </span>

                                            @if ($permission->description)
                                                <span class="text-sm block"
                                                      x-bind:class="{ 'text-blue-700': has('{{ $permission->id }}'), 'text-slate-500': ! has('{{ $permission->id }}') }"
                                                >
                                                    {{ $permission->description }}
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
