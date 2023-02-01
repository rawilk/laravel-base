<div class="space-y-6">
    {{-- role info --}}
    <x-card>
        <x-slot name="header">
            <h2>{{ __('base::roles.create.role_info_title') }}</h2>
            <p class="text-sm text-gray-500">{{ __('base::roles.edit.role_info_subtitle') }}</p>
        </x-slot>

        <x-form-components::form wire:submit.prevent="updateDetails" id="edit-details-form">
            <div>
                {{-- description --}}
                <x-form-components::form-group label="{{ __('base::roles.labels.form.description') }}" name="description" inline optional>
                    <x-form-components::inputs.textarea
                        wire:model.defer="state.description"
                        name="description"
                        placeholder="{{ __('base::roles.labels.form.description_placeholder') }}"
                        maxlength="{{ \Rawilk\LaravelBase\Models\Role::MAX_DESCRIPTION_LENGTH }}"
                    />

                    <x-slot name="helpText">{{ __('base::messages.labels.form.max_characters', ['max' => \Rawilk\LaravelBase\Models\Role::MAX_DESCRIPTION_LENGTH]) }}</x-slot>
                </x-form-components::form-group>
            </div>
        </x-form-components::form>

        <x-slot name="footer">
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="details.updated" />

                <x-blade::button.button type="submit" color="blue" form="edit-details-form" wire:target="updateDetails" right-icon="heroicon-s-check">
                    {{ __('base::messages.update_button') }}
                </x-blade::button.button>
            </div>
        </x-slot>
    </x-card>

    {{-- role permissions --}}
    @can('editPermissions', $role)
        <x-card>
            <x-slot name="header">
                <h2>{{ __('base::roles.create.permissions_title') }}</h2>
                <p class="text-sm text-gray-500">{{ __('base::roles.create.permissions_subtitle') }}</p>
            </x-slot>

            <x-form-components::form wire:submit.prevent="updatePermissions" id="edit-perms-form">
                @include('laravel-base::livewire.roles.partials.permission-options', ['roleName' => $role->name])
            </x-form-components::form>

            @unless ($role->name === \Rawilk\LaravelBase\Models\Role::$adminName)
                <x-slot name="footer">
                    <div class="flex justify-end items-center space-x-4">
                        <x-action-message on="permissions.updated" />

                        <x-blade::button.button type="submit" color="blue" form="edit-perms-form" wire:target="updatePermissions" right-icon="heroicon-s-check">
                            {{ __('base::messages.update_button') }}
                        </x-blade::button.button>
                    </div>
                </x-slot>
            @endunless
        </x-card>
    @endcan
</div>
