<div class="space-y-6">
    {{-- role info --}}
    <x-blade::card.card>
        <x-slot:header>
            <x-blade::card.actions
                title="{{ __('base::roles.create.role_info_title') }}"
                subtitle="{{ __('base::roles.edit.role_info_subtitle') }}"
            />
        </x-slot:header>

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

                    <x-slot:help-text>{{ __('base::messages.labels.form.max_characters', ['max' => \Rawilk\LaravelBase\Models\Role::MAX_DESCRIPTION_LENGTH]) }}</x-slot:help-text>
                </x-form-components::form-group>
            </div>
        </x-form-components::form>

        <x-slot:footer>
            <x-blade::card.footer :reverse="false">
                <x-action-message on="details.updated" />

                <x-blade::button.button type="submit" color="blue" form="edit-details-form" wire:target="updateDetails" right-icon="heroicon-m-check">
                    {{ __('base::messages.update_button') }}
                </x-blade::button.button>
            </x-blade::card.footer>
        </x-slot:footer>
    </x-blade::card.card>

    {{-- role permissions --}}
    @can('editPermissions', $role)
        <x-blade::card.card>
            <x-slot:header>
                <x-blade::card.actions
                    title="{{ __('base::roles.create.permissions_title') }}"
                    subtitle="{{ __('base::roles.create.permissions_subtitle') }}"
                />
            </x-slot:header>

            <x-form-components::form wire:submit.prevent="updatePermissions" id="edit-perms-form">
                @include('laravel-base::livewire.roles.partials.permission-options', ['roleName' => $role->name])
            </x-form-components::form>

            @unless ($role->name === \Rawilk\LaravelBase\Models\Role::$adminName)
                <x-slot:footer>
                    <x-card::card.footer :reverse="false">
                        <x-action-message on="permissions.updated" />

                        <x-blade::button.button type="submit" color="blue" form="edit-perms-form" wire:target="updatePermissions" right-icon="heroicon-m-check">
                            {{ __('base::messages.update_button') }}
                        </x-blade::button.button>
                    </x-card::card.footer>
                </x-slot:footer>
            @endunless
        </x-blade::card.card>
    @endcan
</div>
