<x-form-components::form wire:submit.prevent="createRole">
    <div class="space-y-6">
        {{-- role info --}}
        <x-blade::card.card>
            <x-slot:header>
                <x-blade::card.actions
                    title="{{ __('base::roles.create.role_info_title') }}"
                    subtitle="{{ __('base::roles.create.role_info_subtitle') }}"
                />
            </x-slot:header>

            {{-- name --}}
            <x-form-components::form-group label="{{ __('base::roles.labels.form.name') }}" name="name" inline>
                <x-form-components::inputs.input
                    wire:model.defer="state.name"
                    name="name"
                    required
                    autofocus
                    placeholder="{{ __('base::roles.labels.form.name_placeholder') }}"
                    maxlength="255"
                />
            </x-form-components::form-group>

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
        </x-blade::card.card>

        {{-- permissions --}}
        <x-blade::card.card>
            <x-slot:header>
                <x-blade::card.actions
                    title="{{ __('base::roles.create.permissions_title') }}"
                    subtitle="{{ __('base::roles.create.permissions_subtitle') }}"
                />
            </x-slot:header>

            @include('laravel-base::livewire.roles.partials.permission-options')
        </x-blade::card.card>

        @if ($errors->any())
            <x-alert :type="\Rawilk\LaravelBase\Components\Alerts\Alert::ERROR">
                {{ __('base::messages.labels.form.errors_found') }}
            </x-alert>
        @endif

        <div class="content-container lg:flex lg:flex-row-reverse lg:items-center">
            <span class="flex w-full lg:ml-3 lg:w-auto">
                <x-blade::button.button type="submit" color="blue" wire:target="createRole" right-icon="heroicon-m-check" block>
                    {{ __('base::messages.create_button') }}
                </x-blade::button.button>
            </span>

            <span class="mt-3 flex w-full lg:mt-0 lg:w-auto">
                <x-blade::navigation.link class="w-full" href="{!! route('admin.roles.index') !!}" dark>
                    {{ __('base::messages.cancel_button') }}
                </x-blade::navigation.link>
            </span>
        </div>
    </div>
</x-form-components::form>
