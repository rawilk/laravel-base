<div class="space-y-6">
    {{-- role info --}}
    <x-card>
        <x-slot name="header">
            <h2>{{ __('laravel-base::roles.create.role_info_title') }}</h2>
            <p class="text-sm text-cool-gray-500">{{ __('laravel-base::roles.edit.role_info_subtitle') }}</p>
        </x-slot>

        <x-form-components::form wire:submit.prevent="updateDetails" id="edit-details-form">
            <div>
                {{-- description --}}
                <x-form-components::form-group label="{{ __('laravel-base::roles.labels.form.description') }}" name="description" inline optional>
                    <x-form-components::inputs.textarea
                        wire:model.defer="state.description"
                        name="description"
                        placeholder="{{ __('laravel-base::roles.labels.form.description_placeholder') }}"
                        maxlength="{{ \Rawilk\LaravelBase\Models\Role::MAX_DESCRIPTION_LENGTH }}"
                    />

                    <x-slot name="helpText">{{ __('laravel-base::messages.labels.form.max_characters', ['max' => \Rawilk\LaravelBase\Models\Role::MAX_DESCRIPTION_LENGTH]) }}</x-slot>
                </x-form-components::form-group>
            </div>
        </x-form-components::form>

        <x-slot name="footer">
            <div class="flex justify-end items-center space-x-4">
                <x-action-message on="details.updated" />

                <x-button type="submit" variant="blue" form="edit-details-form" wire:target="updateDetails">
                    <span>{{ __('laravel-base::messages.update_button') }}</span>
                    <x-heroicon-s-check />
                </x-button>
            </div>
        </x-slot>
    </x-card>

    {{-- role permissions --}}
    @can('editPermissions', $role)
        <x-card>
            <x-slot name="header">
                <h2>{{ __('laravel-base::roles.create.permissions_title') }}</h2>
                <p class="text-sm text-cool-gray-500">{{ __('laravel-base::roles.create.permissions_subtitle') }}</p>
            </x-slot>

            <x-form-components::form wire:submit.prevent="updatePermissions" id="edit-perms-form">
                @include('laravel-base::livewire.roles.partials.permission-options', ['roleName' => $role->name])
            </x-form-components::form>

            @unless ($role->name === \Rawilk\LaravelBase\Models\Role::$adminName)
                <x-slot name="footer">
                    <div class="flex justify-end items-center space-x-4">
                        <x-action-message on="permissions.updated" />

                        <x-button type="submit" variant="blue" form="edit-perms-form" wire:target="updatePermissions">
                            <span>{{ __('laravel-base::messages.update_button') }}</span>
                            <x-heroicon-s-check />
                        </x-button>
                    </div>
                </x-slot>
            @endunless
        </x-card>
    @endcan
</div>
