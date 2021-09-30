<div>
    <x-auth.authentication-form title="{{ __('Confirm Password') }}">
        <x-slot name="subTitle">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </x-slot>

        <x-form wire:submit.prevent="confirm">
            {{-- password --}}
            <x-form-group label="{{ __('Password') }}" name="password">
                <x-password
                    wire:model.defer="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
            </x-form-group>

            <div class="mt-6">
                <x-button variant="blue" type="submit" block wire:target="confirm">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </x-form>
    </x-auth.authentication-form>
</div>
