<div>
    <x-auth.authentication-form title="{{ __('Create a new account') }}">
        <x-slot name="subTitle">
            Or <x-link href="{!! route('login') !!}">{{ __('sign in') }}</x-link>
        </x-slot>

        <x-form wire:submit.prevent="register">
            {{-- name --}}
            <x-form-group label="{{ __('Name') }}" name="name">
                <x-input
                    wire:model.defer="name"
                    name="name"
                    maxlength="255"
                    required
                    autofocus
                />
            </x-form-group>

            {{-- email --}}
            <x-form-group label="{{ __('Email address') }}" name="email">
                <x-email
                    wire:model.defer="email"
                    name="email"
                    maxlength="255"
                    required
                />
            </x-form-group>

            {{-- password --}}
            <x-form-group label="{{ __('Password') }}" name="password">
                <x-password
                    wire:model.defer="password"
                    name="password"
                    required
                />
            </x-form-group>

            <div class="mt-6">
                <x-blade::button.button color="blue" type="submit" block wire:target="register">
                    {{ __('Register') }}
                </x-blade::button.button>
            </div>
        </x-form>
    </x-auth.authentication-form>
</div>
